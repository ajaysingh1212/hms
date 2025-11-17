<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAppointmentSlotRequest;
use App\Http\Requests\StoreAppointmentSlotRequest;
use App\Http\Requests\UpdateAppointmentSlotRequest;
use App\Models\AddDoctor;
use App\Models\AppointmentSlot;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AppointmentSlotsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('appointment_slot_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointmentSlots = AppointmentSlot::with(['select_doctor', 'created_by'])->get();

        return view('admin.appointmentSlots.index', compact('appointmentSlots'));
    }

    public function create()
    {
        abort_if(Gate::denies('appointment_slot_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.appointmentSlots.create', compact('select_doctors'));
    }

    public function store(StoreAppointmentSlotRequest $request)
    {
        $appointmentSlot = AppointmentSlot::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $appointmentSlot->id]);
        }

        return redirect()->route('admin.appointment-slots.index');
    }

    public function edit(AppointmentSlot $appointmentSlot)
    {
        abort_if(Gate::denies('appointment_slot_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $appointmentSlot->load('select_doctor', 'created_by');

        return view('admin.appointmentSlots.edit', compact('appointmentSlot', 'select_doctors'));
    }

    public function update(UpdateAppointmentSlotRequest $request, AppointmentSlot $appointmentSlot)
    {
        $appointmentSlot->update($request->all());

        return redirect()->route('admin.appointment-slots.index');
    }

    public function show(AppointmentSlot $appointmentSlot)
    {
        abort_if(Gate::denies('appointment_slot_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointmentSlot->load('select_doctor', 'created_by');

        return view('admin.appointmentSlots.show', compact('appointmentSlot'));
    }

    public function destroy(AppointmentSlot $appointmentSlot)
    {
        abort_if(Gate::denies('appointment_slot_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $appointmentSlot->delete();

        return back();
    }

    public function massDestroy(MassDestroyAppointmentSlotRequest $request)
    {
        $appointmentSlots = AppointmentSlot::find(request('ids'));

        foreach ($appointmentSlots as $appointmentSlot) {
            $appointmentSlot->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('appointment_slot_create') && Gate::denies('appointment_slot_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AppointmentSlot();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
