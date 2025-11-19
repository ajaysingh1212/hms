<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAppointmentRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\AddDoctor;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\DepartmentName;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    // =========================================================
    // INDEX
    // =========================================================
    public function index()
    {
        abort_if(Gate::denies('appointment_access'), 403);

        $appointments = Appointment::with(['department', 'doctor', 'available_slots', 'created_by'])->get();

        return view('admin.appointments.index', compact('appointments'));
    }


    // =========================================================
    // CREATE
    // =========================================================
    public function create()
    {
        abort_if(Gate::denies('appointment_create'), 403);

        $departments = DepartmentName::pluck('name', 'id');

        return view('admin.appointments.create', compact('departments'));
    }




    // =========================================================
    // AJAX: Get Doctors by Department
    // =========================================================
    public function getDoctors(Request $request)
    {
        return AddDoctor::where('select_department_id', $request->department_id)
            ->pluck('doctor_name', 'id');
    }



    // =========================================================
    // AJAX: Get Doctor Details + Next Available Date + Slot History
    // =========================================================
    public function getDoctorDetails(Request $request)
    {
        $doctor = AddDoctor::find($request->doctor_id);
        if (!$doctor) return ['error' => 'Doctor not found'];

        $availableDays = json_decode($doctor->available_days, true) ?: [];

        // Today
        $today     = Carbon::today();
        $todayYmd  = $today->format('Y-m-d');
        $todayName = strtolower($today->format('l'));

        $slotsDB = AppointmentSlot::where('select_doctor_id', $doctor->id)
            ->orderBy('select_time')
            ->get(['id','select_time']);

        // All appointments for this doctor
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->get(['date','doctor_slots']);

        $slotInfo = [];
        $todayFreeSlot = false;
        $todayHasSlot  = in_array($todayName, $availableDays);

        foreach ($slotsDB as $slot) {

            $bookedToday = false;

            foreach ($appointments as $a) {

                $aDate = Carbon::parse($a->date)->format('Y-m-d');
                $docSlots = is_array($a->doctor_slots)
                    ? $a->doctor_slots
                    : json_decode($a->doctor_slots, true);

                if (!is_array($docSlots)) continue;

                if (in_array($slot->select_time, $docSlots)) {
                    if ($aDate == $todayYmd) {
                        $bookedToday = true;
                    }
                }
            }

            if (!$bookedToday && $todayHasSlot) {
                $todayFreeSlot = true;
            }

            $slotInfo[] = [
                'id'           => $slot->id,
                'select_time'  => $slot->select_time,
                'booked_today' => $bookedToday,
            ];
        }


        // AUTO DATE LOGIC
        if ($todayHasSlot && $todayFreeSlot) {
            $selectedDate = $todayYmd;
        } else {
            // find next available day
            $selectedDate = null;

            for ($i=1;$i<=7;$i++) {
                $next = $today->copy()->addDays($i);
                if (in_array(strtolower($next->format('l')), $availableDays)) {
                    $selectedDate = $next->format('Y-m-d');
                    break;
                }
            }

            if (!$selectedDate) $selectedDate = $todayYmd;
        }


        return [
            'doctor_name' => $doctor->doctor_name,
            'experience'  => $doctor->experience,
            'qualifications' => $doctor->qualifications,
            'phone'       => $doctor->phone,
            'doctor_fee'  => $doctor->doctor_fee,
            'available_days' => $availableDays,
            'slots'       => $slotInfo,
            'selected_appointment_date' => $selectedDate,
        ];
    }




    // =========================================================
    // AJAX: Get Slots for a Specific Date
    // =========================================================
    public function getAvailableSlots(Request $request)
    {
        $doctor = AddDoctor::find($request->doctor_id);
        if (!$doctor) return ['error' => 'Doctor not found'];

        $day = strtolower(Carbon::parse($request->date)->format('l'));
        $availableDays = json_decode($doctor->available_days, true) ?: [];

        if (!in_array($day, $availableDays)) {
            return [
                'available' => [],
                'booked'    => []
            ];
        }

        $allSlots = AppointmentSlot::where('select_doctor_id', $doctor->id)->get();

        $bookedSlotIds = Appointment::where('doctor_id', $doctor->id)
            ->where('date', Carbon::parse($request->date)->format('Y-m-d'))
            ->pluck('available_slots_id')
            ->toArray();

        return [
            'available' => $allSlots->whereNotIn('id', $bookedSlotIds)->values(),
            'booked'    => $allSlots->whereIn('id', $bookedSlotIds)->values(),
        ];
    }




    // =========================================================
    // STORE (Create)
    // =========================================================
    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->all();

        $data['available_days'] = json_decode($request->available_days, true);
        $data['doctor_slots']   = json_decode($request->doctor_slots, true);
        $data['created_by_id']  = auth()->id();

        $appointment = Appointment::create($data);

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $appointment->id]);
        }

        return redirect()->route('admin.appointments.index');
    }




    // =========================================================
    // EDIT
    // =========================================================
    public function edit(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_edit'), 403);

        $departments = DepartmentName::pluck('name', 'id');

        $appointment->load('department','doctor','available_slots','created_by');

        return view('admin.appointments.edit', compact('appointment','departments'));
    }




    // =========================================================
    // UPDATE
    // =========================================================
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $data = $request->all();

        $data['available_days'] = json_decode($request->available_days, true);
        $data['doctor_slots']   = json_decode($request->doctor_slots, true);

        $appointment->update($data);

        return redirect()->route('admin.appointments.index');
    }




    // =========================================================
    // SHOW
    // =========================================================
    public function show(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_show'), 403);

        $appointment->load('department','doctor','available_slots','created_by');

        return view('admin.appointments.show', compact('appointment'));
    }



    // =========================================================
    // DELETE
    // =========================================================
    public function destroy(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_delete'), 403);

        $appointment->delete();

        return back();
    }


    // =========================================================
    // MASS DELETE
    // =========================================================
    public function massDestroy(MassDestroyAppointmentRequest $request)
    {
        Appointment::whereIn('id', request('ids'))->delete();
        return response(null, 204);
    }



    // =========================================================
    // CKEDITOR UPLOAD
    // =========================================================
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('appointment_create') && Gate::denies('appointment_edit'), 403);

        $model = new Appointment();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json([
            'id'  => $media->id,
            'url' => $media->getUrl()
        ], 201);
    }
}
