<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdAdmissionRequest;
use App\Http\Requests\StoreIpdAdmissionRequest;
use App\Http\Requests\UpdateIpdAdmissionRequest;
use App\Models\AddDoctor;
use App\Models\Appointment;
use App\Models\IpdAdmission;
use App\Models\IpdBed;
use App\Models\IpdRoom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdAdmissionsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_admission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdAdmissions = IpdAdmission::with(['patient', 'doctor', 'room', 'bed', 'created_by', 'media'])->get();

        return view('admin.ipdAdmissions.index', compact('ipdAdmissions'));
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_admission_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = Appointment::pluck('patient_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rooms = IpdRoom::pluck('room_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        $beds = IpdBed::pluck('bed_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdAdmissions.create', compact('beds', 'doctors', 'patients', 'rooms'));
    }

    public function getAppointmentDetails(Request $request)
    {
        $patientVal = $request->get('patient_id');

        if (!$patientVal) {
            return response()->json(['message' => 'patient_id required'], 422);
        }

        // Try direct appointment id first
        $appointment = Appointment::with(['doctor.select_department','department'])->find($patientVal);

        // Fallback: maybe patient_id is actually a patient_number; get latest appointment for that patient
        if (!$appointment) {
            $appointment = Appointment::with(['doctor.select_department','department'])
                            ->where('patient_number', $patientVal)
                            ->orderBy('date', 'desc')
                            ->first();
        }

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        // Prepare doctor data
        $doctor = null;
        if ($appointment->doctor) {
            $doctor = [
                'id' => $appointment->doctor->id,
                'name' => $appointment->doctor->doctor_name,
                'doctor_fee' => $appointment->doctor->doctor_fee,
                'experience' => $appointment->doctor->experience,
                'qualifications' => $appointment->doctor->qualifications,
                'phone' => $appointment->doctor->phone,
                'phone_alt' => $appointment->doctor->phone_alt,
                'description' => $appointment->doctor->description,
                'available_days' => $appointment->doctor->available_days,
                'department_id' => $appointment->doctor->select_department_id,
                'department_name' => optional($appointment->doctor->select_department)->name,
            ];
        }

        // Department info (from appointment or doctor's department fallback)
        $department = null;
        if ($appointment->department) {
            $department = [
                'id' => $appointment->department->id,
                'name' => $appointment->department->name,
            ];
        } elseif ($appointment->doctor && $appointment->doctor->select_department) {
            $dep = $appointment->doctor->select_department;
            $department = ['id' => $dep->id, 'name' => $dep->name];
        }

        // Additional appointment small summary
        $summary = [
            'appointment_id' => $appointment->id,
            'patient_number' => $appointment->patient_number,
            'created_at' => $appointment->created_at ? $appointment->created_at->format('Y-m-d H:i:s') : null,
            'doctor_slot' => $appointment->doctor_slots, // json casted in model
            'available_days' => $appointment->available_days,
        ];

        return response()->json([
            'appointment' => [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient_name,
                'mobile_number' => $appointment->mobile_number,
                'date' => $appointment->date,
                'reason_for_visit' => $appointment->reason_for_visit,
                'appointment_type' => $appointment->appointment_type,
                'status' => $appointment->status,
                'department_id' => $appointment->department_id,
                'doctor_id' => $appointment->doctor_id,
            ],
            'doctor' => $doctor,
            'department' => $department,
            'summary' => $summary,
        ], 200);
    }

    public function getRoomDetails(Request $request)
    {
        $roomId = $request->get('room_id');

        if (!$roomId) {
            return response()->json(['message' => 'room_id required'], 422);
        }

        $room = IpdRoom::find($roomId);

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        $beds = IpdBed::where('room_id', $room->id)
                    ->where('status', 'available')
                    ->get(['id','bed_no','charges_per_day','status','notes']);

        return response()->json([
            'room' => [
                'id' => $room->id,
                'room_no' => $room->room_no,
                'ward_type' => $room->ward_type,
                'charges_per_day' => $room->charges_per_day,
                'status' => $room->status,
                'notes' => $room->notes,
            ],
            'beds' => $beds,
        ], 200);
    }


    // store method (snippet showing ipd_number fallback and attachments handling)
public function store(Request $request)
{
    $rules = [
        'patient_id' => 'required',
        'doctor_id' => 'nullable|exists:add_doctors,id',
        'ipd_number' => 'nullable|string|unique:ipd_admissions,ipd_number',
        'admission_date' => 'nullable|date_format:' . config('panel.date_format'),
        'admission_time' => 'nullable|string',
        'room_id' => 'nullable|exists:ipd_rooms,id',
        'bed_id' => 'nullable|exists:ipd_beds,id',
        'status' => 'nullable|in:admitted,discharged,transferred',
        'attechment.*' => 'nullable|string',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();
    try {
        $data = $request->all();

        // ============================
        // AUTO GENERATE IPD NUMBER
        // ============================
        if (empty($data['ipd_number'])) {
            do {
                $rand = 'IPD-' . mt_rand(10000000, 99999999);
            } while (IpdAdmission::where('ipd_number', $rand)->exists());

            $data['ipd_number'] = $rand;
        }

        // ============================
        // FORMAT DATE
        // ============================
        if (!empty($data['admission_date'])) {
            $data['admission_date'] =
                Carbon::createFromFormat(config('panel.date_format'), $data['admission_date'])
                ->format('Y-m-d');
        }

        // ============================
        // CREATE IPD ADMISSION
        // ============================
        $ipd = IpdAdmission::create($data);

        // ============================
        // BOOK THE SELECTED BED
        // ============================
        if (!empty($request->bed_id)) {
            DB::table('ipd_beds')
                ->where('id', $request->bed_id)
                ->update(['status' => 'booked']);
        }

        // ============================
        // ADD ATTACHMENTS
        // ============================
        if ($request->has('attechment')) {
            foreach ($request->input('attechment') as $filename) {
                $path = storage_path('tmp/uploads/' . $filename);
                if (file_exists($path)) {
                    $ipd->addMedia($path)->toMediaCollection('attechment');
                }
            }
        }

        // ============================
        // ATTACH CKEDITOR MEDIA
        // ============================
        if ($media = $request->input('ck-media', false)) {
            foreach ($media as $file) {
                $m = \Spatie\MediaLibrary\Models\Media::find($file);
                if ($m) {
                    $m->model_id = $ipd->id;
                    $m->model_type = IpdAdmission::class;
                    $m->save();
                }
            }
        }

        DB::commit();
        return redirect()->route('admin.ipd-admissions.index')->with('success', 'IPD created successfully');

    } catch (\Exception $e) {

        DB::rollBack();
        return redirect()->back()->with('error', $e->getMessage())->withInput();
    }
}



    public function edit(IpdAdmission $ipdAdmission)
    {
        $patients = Appointment::pluck('patient_name', 'id')->prepend("Please select", "");
        $doctors  = AddDoctor::pluck('doctor_name', 'id')->prepend("Please select", "");
        $rooms    = IpdRoom::where("status", "available")->pluck('room_no', 'id')->prepend("Please select", "");

        $beds = IpdBed::where('room_id', $ipdAdmission->room_id)
            ->where('status', 'available')
            ->get()
            ->pluck('bed_no', 'id')
            ->prepend("Please select", "");

        $ipdAdmission->load(['patient', 'doctor', 'room', 'bed']);

        return view('admin.ipdAdmissions.edit', compact('ipdAdmission', 'patients', 'doctors', 'rooms', 'beds'));
    }

    /* ----------------------------------------------------
     * UPDATE
     * ---------------------------------------------------- */
public function update(Request $request, IpdAdmission $ipdAdmission)
{
    $rules = [
        'patient_id' => 'required',
        'doctor_id' => 'nullable|exists:add_doctors,id',
        'ipd_number' => 'nullable|string|unique:ipd_admissions,ipd_number,' . $ipdAdmission->id,
        'admission_date' => 'nullable|string',
        'admission_time' => 'nullable|string',
        'room_id' => 'nullable|exists:ipd_rooms,id',
        'bed_id' => 'nullable|exists:ipd_beds,id',
        'attechment.*' => 'nullable|string',
        'status' => 'nullable|string',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();
    try {

        $data = $request->all();

        // Format date
        if (!empty($data['admission_date'])) {
            $data['admission_date'] = Carbon::createFromFormat(config('panel.date_format'), $data['admission_date'])->format('Y-m-d');
        }

        // ===== BED STATUS UPDATE LOGIC =====

        $old_bed_id = $ipdAdmission->bed_id;   // currently assigned bed
        $new_bed_id = $request->bed_id;        // bed selected from form

        // 1. OLD BED → SET TO "available"
        if ($old_bed_id && $old_bed_id != $new_bed_id) {
            DB::table('ipd_beds')->where('id', $old_bed_id)->update([
                'status' => 'available'
            ]);
        }

        // 2. NEW BED → SET TO "booked"
        if (!empty($new_bed_id)) {
            DB::table('ipd_beds')->where('id', $new_bed_id)->update([
                'status' => 'booked'
            ]);
        }

        // Update admission
        $ipdAdmission->update($data);

        // Attachments
        if ($request->has('attechment')) {

            // Delete old files
            foreach ($ipdAdmission->getMedia('attechment') as $media) {
                $media->delete();
            }

            // Add new files
            foreach ($request->input('attechment') as $file) {
                $ipdAdmission->addMedia(storage_path('tmp/uploads/' . $file))
                    ->toMediaCollection('attechment');
            }
        }

        DB::commit();

        return redirect()->route('admin.ipd-admissions.index')
            ->with('success', 'IPD Admission Updated Successfully');

    } catch (\Exception $e) {

        DB::rollBack();
        dd($e->getMessage());
    }
}

    public function show(IpdAdmission $ipdAdmission)
    {
        abort_if(Gate::denies('ipd_admission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdAdmission->load('patient', 'doctor', 'room', 'bed', 'created_by', 'ipdIpdMedications', 'ipdIpdVitals', 'ipdIpdTests');

        return view('admin.ipdAdmissions.show', compact('ipdAdmission'));
    }

    public function destroy(IpdAdmission $ipdAdmission)
    {
        abort_if(Gate::denies('ipd_admission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdAdmission->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdAdmissionRequest $request)
    {
        $ipdAdmissions = IpdAdmission::find(request('ids'));

        foreach ($ipdAdmissions as $ipdAdmission) {
            $ipdAdmission->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_admission_create') && Gate::denies('ipd_admission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdAdmission();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
