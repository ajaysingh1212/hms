<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOpdVisitRequest;
use App\Http\Requests\StoreOpdVisitRequest;
use App\Http\Requests\UpdateOpdVisitRequest;
use App\Models\AddDoctor;
use App\Models\Appointment;
use App\Models\OpdVisit;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OpdVisitsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_visit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdVisits = OpdVisit::with(['patient', 'doctor', 'created_by', 'media'])->get();

        return view('admin.opdVisits.index', compact('opdVisits'));
    }
  public function appointmentDetails(Appointment $appointment)
{
    $appointment->load(['doctor', 'available_slots']);

    $doctor = $appointment->doctor;

    // Ensure available_days is always an array
    $available_days = [];
    if ($doctor) {
        if (is_array($doctor->available_days)) {
            $available_days = $doctor->available_days;
        } elseif (is_string($doctor->available_days)) {
            $decoded = json_decode($doctor->available_days, true);
            $available_days = is_array($decoded) ? $decoded : [$doctor->available_days];
        } elseif ($doctor->available_days === null) {
            $available_days = [];
        } else {
            // object or other type — try casting to array
            $available_days = (array) $doctor->available_days;
        }
    }

    // Ensure doctor_slots is an array as well (from appointment)
    $doctor_slots = [];
    if (is_array($appointment->doctor_slots)) {
        $doctor_slots = $appointment->doctor_slots;
    } elseif (is_string($appointment->doctor_slots)) {
        $decoded = json_decode($appointment->doctor_slots, true);
        $doctor_slots = is_array($decoded) ? $decoded : [$appointment->doctor_slots];
    } elseif ($appointment->doctor_slots === null) {
        $doctor_slots = [];
    } else {
        $doctor_slots = (array) $appointment->doctor_slots;
    }

    $slot = $appointment->available_slots;

    return response()->json([
        'success' => true,
        'appointment' => [
            'id' => $appointment->id,
            'patient_name' => $appointment->patient_name,
            'mobile_number' => $appointment->mobile_number,
            'date' => $appointment->date,
            'reason_for_visit' => nl2br(strip_tags($appointment->reason_for_visit )),
            'appointment_type' => $appointment->appointment_type,
            'patient_number' => $appointment->patient_number,
            'doctor_slots' => $doctor_slots,
        ],
        'doctor' => $doctor ? [
            'id' => $doctor->id,
            'department' => $doctor->select_department->name ?? null,
            'doctor_name' => $doctor->doctor_name,
            'available_days' => array_values($available_days), // ensure indexed array
            'appointment_slot_duration' => $slot?->duration ?? $slot?->slot_time ?? null,
            'max_patients_per_day' => $doctor->max_patients_per_day ?? null,
            'doctor_fee' => $doctor->doctor_fee ?? $doctor->fee ?? null,
            'description' => $doctor->description ?? null,
            'phone' => $doctor->phone ?? null,
            'phone_alt' => $doctor->phone_alt ?? null,
            'experience' => $doctor->experience ?? null,
            'qualifications' => $doctor->qualifications ?? null,
        ] : null,
    ], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
}

    public function create()
    {
        abort_if(Gate::denies('opd_visit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = Appointment::pluck('patient_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.opdVisits.create', compact('doctors', 'patients'));
    }

    public function store(StoreOpdVisitRequest $request)
    {
        $data = $request->all();
        $data['status'] = 'open'; // default status
        $data['created_by_id'] = auth()->id(); // set created_by to current user
       $data['opd_id'] = 'OPD-' . str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);


        $opdVisit = OpdVisit::create($data);

        foreach ($request->input('attechment', []) as $file) {
            $opdVisit->addMedia(storage_path('tmp/uploads/' . basename($file)))
                ->toMediaCollection('attechment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $opdVisit->id]);
        }

        return redirect()->route('admin.opd-visits.index');
    }


    public function edit(OpdVisit $opdVisit)
    {
        abort_if(Gate::denies('opd_visit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = Appointment::pluck('patient_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $opdVisit->load('patient', 'doctor', 'created_by');

        return view('admin.opdVisits.edit', compact('doctors', 'opdVisit', 'patients'));
    }

    public function update(UpdateOpdVisitRequest $request, OpdVisit $opdVisit)
    {
        $opdVisit->update($request->all());

        if (count($opdVisit->attechment) > 0) {
            foreach ($opdVisit->attechment as $media) {
                if (! in_array($media->file_name, $request->input('attechment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $opdVisit->attechment->pluck('file_name')->toArray();
        foreach ($request->input('attechment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $opdVisit->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
            }
        }

        return redirect()->route('admin.opd-visits.index');
    }

    public function show(OpdVisit $opdVisit)
    {
        abort_if(Gate::denies('opd_visit_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdVisit->load('patient', 'doctor', 'created_by');

        return view('admin.opdVisits.show', compact('opdVisit'));
    }

    public function destroy(OpdVisit $opdVisit)
    {
        abort_if(Gate::denies('opd_visit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdVisit->delete();

        return back();
    }

    public function massDestroy(MassDestroyOpdVisitRequest $request)
    {
        $opdVisits = OpdVisit::find(request('ids'));

        foreach ($opdVisits as $opdVisit) {
            $opdVisit->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('opd_visit_create') && Gate::denies('opd_visit_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OpdVisit();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
