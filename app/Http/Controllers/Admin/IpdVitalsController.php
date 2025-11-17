<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdVitalRequest;
use App\Http\Requests\StoreIpdVitalRequest;
use App\Http\Requests\UpdateIpdVitalRequest;
use App\Models\IpdAdmission;
use App\Models\IpdVital;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdVitalsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_vital_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdVitals = IpdVital::with(['ipd', 'created_by', 'media'])->get();

        return view('admin.ipdVitals.index', compact('ipdVitals'));
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_vital_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdVitals.create', compact('ipds'));
    }

    public function store(StoreIpdVitalRequest $request)
    {
        $ipdVital = IpdVital::create($request->all());

        if ($request->input('attechments', false)) {
            $ipdVital->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdVital->id]);
        }

        return redirect()->route('admin.ipd-vitals.index');
    }

    public function edit(IpdVital $ipdVital)
    {
        abort_if(Gate::denies('ipd_vital_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdVital->load('ipd', 'created_by');

        return view('admin.ipdVitals.edit', compact('ipdVital', 'ipds'));
    }

    public function update(UpdateIpdVitalRequest $request, IpdVital $ipdVital)
    {
        $ipdVital->update($request->all());

        if ($request->input('attechments', false)) {
            if (! $ipdVital->attechments || $request->input('attechments') !== $ipdVital->attechments->file_name) {
                if ($ipdVital->attechments) {
                    $ipdVital->attechments->delete();
                }
                $ipdVital->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
            }
        } elseif ($ipdVital->attechments) {
            $ipdVital->attechments->delete();
        }

        return redirect()->route('admin.ipd-vitals.index');
    }

    public function show(IpdVital $ipdVital)
    {
        abort_if(Gate::denies('ipd_vital_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdVital->load('ipd', 'created_by');

        return view('admin.ipdVitals.show', compact('ipdVital'));
    }

    public function destroy(IpdVital $ipdVital)
    {
        abort_if(Gate::denies('ipd_vital_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdVital->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdVitalRequest $request)
    {
        $ipdVitals = IpdVital::find(request('ids'));

        foreach ($ipdVitals as $ipdVital) {
            $ipdVital->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_vital_create') && Gate::denies('ipd_vital_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdVital();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
