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

    public function index()
    {
        abort_if(Gate::denies('appointment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointments = Appointment::with(['department', 'doctor', 'available_slots', 'created_by'])->get();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        abort_if(Gate::denies('appointment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departments = DepartmentName::pluck('name', 'id');

        $doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $available_slots = AppointmentSlot::pluck('select_time', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.appointments.create', compact('available_slots', 'departments', 'doctors'));
    }
    public function getDoctors(Request $request)
{
    return AddDoctor::where('select_department_id', $request->department_id)
        ->pluck('doctor_name', 'id');
}

public function getDoctorDetails(Request $request)
{
    $doctor = AddDoctor::find($request->doctor_id);

    if (!$doctor) {
        return ['error' => 'Doctor not found'];
    }

    // All slots for doctor (id + select_time)
    $slots = AppointmentSlot::where('select_doctor_id', $doctor->id)
        ->orderBy('select_time', 'ASC')
        ->get(['id', 'select_time']);

    // Load all appointments for this doctor (we will inspect doctor_slots & available_slots_id)
    $appointments = Appointment::where('doctor_id', $doctor->id)
        ->get(['date', 'available_slots_id', 'doctor_slots']);

    $today = Carbon::today()->format('Y-m-d');

    // Prepare slot info: for each slot, collect all booked dates (unique)
    $slotInfo = [];

    foreach ($slots as $s) {
        $bookedDates = [];

        foreach ($appointments as $a) {
            // normalize appointment date to Y-m-d
            if (!$a->date) {
                continue;
            }

            try {
                $aDate = Carbon::parse($a->date)->format('Y-m-d');
            } catch (\Exception $e) {
                // if parsing fails, skip
                continue;
            }

            $isBooked = false;

            // 1) Check if appointment saved available_slots_id (slot id)
            if (!empty($a->available_slots_id) && $a->available_slots_id == $s->id) {
                $isBooked = true;
            } else {
                // 2) Or check if appointment->doctor_slots JSON contains this slot's select_time
                $docSlots = $a->doctor_slots;
                if (!is_array($docSlots)) {
                    $docSlots = json_decode($a->doctor_slots, true);
                }
                if (is_array($docSlots) && in_array($s->select_time, $docSlots)) {
                    $isBooked = true;
                }
            }

            if ($isBooked) {
                $bookedDates[] = $aDate;
            }
        }

        // make unique and sorted (descending: recent first)
        $bookedDates = array_values(array_unique($bookedDates));
        usort($bookedDates, function($a, $b){
            return strcmp($b, $a);
        });

        $slotInfo[] = [
            'id' => $s->id,
            'select_time' => $s->select_time,
            'booked_dates' => $bookedDates, // list of Y-m-d strings
            'booked_today' => in_array($today, $bookedDates),
        ];
    }

    return [
        'doctor_name'    => $doctor->doctor_name,
        'experience'     => $doctor->experience,
        'qualifications' => $doctor->qualifications,
        'phone'          => $doctor->phone,
        'doctor_fee'     => $doctor->doctor_fee,
        'available_days' => json_decode($doctor->available_days, true),
        'slots'          => $slotInfo,
    ];
}


public function getAvailableSlots(Request $request)
{
    $doctor = AddDoctor::find($request->doctor_id);

    if (!$doctor) {
        return ['error' => 'Doctor not found'];
    }

    $day = strtolower(Carbon::parse($request->date)->format('l'));

    $availableDays = json_decode($doctor->available_days, true);

    if (!$availableDays || !in_array($day, $availableDays)) {
        return [
            'message'  => 'Doctor not available on this day',
            'day'      => $day,
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
        'day'        => $day,
        'available'  => $allSlots->whereNotIn('id', $bookedSlotIds)->values(),
        'booked'     => $allSlots->whereIn('id', $bookedSlotIds)->values(),
        'doctorDays' => $availableDays,
    ];
}



    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->all();
        // dd($data);
        // JSON decode for model
        $data['available_days']  = json_decode($request->available_days, true);
        $data['doctor_slots']    = json_decode($request->doctor_slots, true);

        $data['created_by_id'] = auth()->id();

        $appointment = Appointment::create($data);

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $appointment->id]);
        }

        return redirect()->route('admin.appointments.index');
    }


    public function edit(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departments = DepartmentName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $available_slots = AppointmentSlot::pluck('select_time', 'id')->prepend(trans('global.pleaseSelect'), '');

        $appointment->load('department', 'doctor', 'available_slots', 'created_by');

        return view('admin.appointments.edit', compact('appointment', 'available_slots', 'departments', 'doctors'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->all());

        return redirect()->route('admin.appointments.index');
    }

    public function show(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointment->load('department', 'doctor', 'available_slots', 'created_by', 'patientOpdVisits', 'patientIpdAdmissions');

        return view('admin.appointments.show', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        abort_if(Gate::denies('appointment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointment->delete();

        return back();
    }

    public function massDestroy(MassDestroyAppointmentRequest $request)
    {
        $appointments = Appointment::find(request('ids'));

        foreach ($appointments as $appointment) {
            $appointment->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('appointment_create') && Gate::denies('appointment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Appointment();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
