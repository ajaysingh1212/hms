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

    public function create()
    {
        abort_if(Gate::denies('opd_visit_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $patients = Appointment::pluck('patient_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $doctors = AddDoctor::pluck('doctor_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.opdVisits.create', compact('doctors', 'patients'));
    }

    public function store(StoreOpdVisitRequest $request)
    {
        $opdVisit = OpdVisit::create($request->all());

        foreach ($request->input('attechment', []) as $file) {
            $opdVisit->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
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
