<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOpdPrescriptionRequest;
use App\Http\Requests\StoreOpdPrescriptionRequest;
use App\Http\Requests\UpdateOpdPrescriptionRequest;
use App\Models\Medicine;
use App\Models\OpdPrescription;
use App\Models\OpdVisit;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OpdPrescriptionsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_prescription_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdPrescriptions = OpdPrescription::with(['opd', 'medicines', 'created_by', 'media'])->get();

        return view('admin.opdPrescriptions.index', compact('opdPrescriptions'));
    }

    public function create()
    {
        abort_if(Gate::denies('opd_prescription_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('visit_date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $medicines = Medicine::pluck('name', 'id');

        return view('admin.opdPrescriptions.create', compact('medicines', 'opds'));
    }

    public function store(StoreOpdPrescriptionRequest $request)
    {
        $opdPrescription = OpdPrescription::create($request->all());
        $opdPrescription->medicines()->sync($request->input('medicines', []));
        foreach ($request->input('attechment', []) as $file) {
            $opdPrescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $opdPrescription->id]);
        }

        return redirect()->route('admin.opd-prescriptions.index');
    }

    public function edit(OpdPrescription $opdPrescription)
    {
        abort_if(Gate::denies('opd_prescription_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('visit_date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $medicines = Medicine::pluck('name', 'id');

        $opdPrescription->load('opd', 'medicines', 'created_by');

        return view('admin.opdPrescriptions.edit', compact('medicines', 'opdPrescription', 'opds'));
    }

    public function update(UpdateOpdPrescriptionRequest $request, OpdPrescription $opdPrescription)
    {
        $opdPrescription->update($request->all());
        $opdPrescription->medicines()->sync($request->input('medicines', []));
        if (count($opdPrescription->attechment) > 0) {
            foreach ($opdPrescription->attechment as $media) {
                if (! in_array($media->file_name, $request->input('attechment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $opdPrescription->attechment->pluck('file_name')->toArray();
        foreach ($request->input('attechment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $opdPrescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
            }
        }

        return redirect()->route('admin.opd-prescriptions.index');
    }

    public function show(OpdPrescription $opdPrescription)
    {
        abort_if(Gate::denies('opd_prescription_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdPrescription->load('opd', 'medicines', 'created_by');

        return view('admin.opdPrescriptions.show', compact('opdPrescription'));
    }

    public function destroy(OpdPrescription $opdPrescription)
    {
        abort_if(Gate::denies('opd_prescription_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdPrescription->delete();

        return back();
    }

    public function massDestroy(MassDestroyOpdPrescriptionRequest $request)
    {
        $opdPrescriptions = OpdPrescription::find(request('ids'));

        foreach ($opdPrescriptions as $opdPrescription) {
            $opdPrescription->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('opd_prescription_create') && Gate::denies('opd_prescription_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OpdPrescription();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
