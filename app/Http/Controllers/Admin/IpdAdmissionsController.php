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
use Gate;
use Illuminate\Http\Request;
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

    public function store(StoreIpdAdmissionRequest $request)
    {
        $ipdAdmission = IpdAdmission::create($request->all());

        if ($request->input('attechment', false)) {
            $ipdAdmission->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechment'))))->toMediaCollection('attechment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdAdmission->id]);
        }

        return redirect()->route('admin.ipd-admissions.index');
    }

    public function edit(IpdAdmission $ipdAdmission)
    {
        abort_if(Gate::denies('ipd_admission_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = Appointment::pluck('patient_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $rooms = IpdRoom::pluck('room_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        $beds = IpdBed::pluck('bed_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdAdmission->load('patient', 'doctor', 'room', 'bed', 'created_by');

        return view('admin.ipdAdmissions.edit', compact('beds', 'doctors', 'ipdAdmission', 'patients', 'rooms'));
    }

    public function update(UpdateIpdAdmissionRequest $request, IpdAdmission $ipdAdmission)
    {
        $ipdAdmission->update($request->all());

        if ($request->input('attechment', false)) {
            if (! $ipdAdmission->attechment || $request->input('attechment') !== $ipdAdmission->attechment->file_name) {
                if ($ipdAdmission->attechment) {
                    $ipdAdmission->attechment->delete();
                }
                $ipdAdmission->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechment'))))->toMediaCollection('attechment');
            }
        } elseif ($ipdAdmission->attechment) {
            $ipdAdmission->attechment->delete();
        }

        return redirect()->route('admin.ipd-admissions.index');
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
