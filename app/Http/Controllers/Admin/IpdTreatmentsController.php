<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdTreatmentRequest;
use App\Http\Requests\StoreIpdTreatmentRequest;
use App\Http\Requests\UpdateIpdTreatmentRequest;
use App\Models\IpdAdmission;
use App\Models\IpdTreatment;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdTreatmentsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_treatment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTreatments = IpdTreatment::with(['ipd', 'created_by', 'media'])->get();

        return view('admin.ipdTreatments.index', compact('ipdTreatments'));
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_treatment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdTreatments.create', compact('ipds'));
    }

    public function store(StoreIpdTreatmentRequest $request)
    {
        $ipdTreatment = IpdTreatment::create($request->all());

        if ($request->input('attechment', false)) {
            $ipdTreatment->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechment'))))->toMediaCollection('attechment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdTreatment->id]);
        }

        return redirect()->route('admin.ipd-treatments.index');
    }

    public function edit(IpdTreatment $ipdTreatment)
    {
        abort_if(Gate::denies('ipd_treatment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdTreatment->load('ipd', 'created_by');

        return view('admin.ipdTreatments.edit', compact('ipdTreatment', 'ipds'));
    }

    public function update(UpdateIpdTreatmentRequest $request, IpdTreatment $ipdTreatment)
    {
        $ipdTreatment->update($request->all());

        if ($request->input('attechment', false)) {
            if (! $ipdTreatment->attechment || $request->input('attechment') !== $ipdTreatment->attechment->file_name) {
                if ($ipdTreatment->attechment) {
                    $ipdTreatment->attechment->delete();
                }
                $ipdTreatment->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechment'))))->toMediaCollection('attechment');
            }
        } elseif ($ipdTreatment->attechment) {
            $ipdTreatment->attechment->delete();
        }

        return redirect()->route('admin.ipd-treatments.index');
    }

    public function show(IpdTreatment $ipdTreatment)
    {
        abort_if(Gate::denies('ipd_treatment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTreatment->load('ipd', 'created_by');

        return view('admin.ipdTreatments.show', compact('ipdTreatment'));
    }

    public function destroy(IpdTreatment $ipdTreatment)
    {
        abort_if(Gate::denies('ipd_treatment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTreatment->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdTreatmentRequest $request)
    {
        $ipdTreatments = IpdTreatment::find(request('ids'));

        foreach ($ipdTreatments as $ipdTreatment) {
            $ipdTreatment->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_treatment_create') && Gate::denies('ipd_treatment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdTreatment();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
