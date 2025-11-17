<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAppointmentSlotRequest;
use App\Http\Requests\UpdateAppointmentSlotRequest;
use App\Http\Resources\Admin\AppointmentSlotResource;
use App\Models\AppointmentSlot;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentSlotsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('appointment_slot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AppointmentSlotResource(AppointmentSlot::with(['select_doctor', 'created_by'])->get());
    }

    public function store(StoreAppointmentSlotRequest $request)
    {
        $appointmentSlot = AppointmentSlot::create($request->all());

        return (new AppointmentSlotResource($appointmentSlot))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AppointmentSlot $appointmentSlot)
    {
        abort_if(Gate::denies('appointment_slot_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AppointmentSlotResource($appointmentSlot->load(['select_doctor', 'created_by']));
    }

    public function update(UpdateAppointmentSlotRequest $request, AppointmentSlot $appointmentSlot)
    {
        $appointmentSlot->update($request->all());

        return (new AppointmentSlotResource($appointmentSlot))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AppointmentSlot $appointmentSlot)
    {
        abort_if(Gate::denies('appointment_slot_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointmentSlot->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
