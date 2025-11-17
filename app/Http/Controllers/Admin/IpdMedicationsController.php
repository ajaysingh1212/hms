<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdMedicationRequest;
use App\Http\Requests\StoreIpdMedicationRequest;
use App\Http\Requests\UpdateIpdMedicationRequest;
use App\Models\IpdAdmission;
use App\Models\IpdMedication;
use App\Models\Medicine;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdMedicationsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_medication_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdMedications = IpdMedication::with(['ipd', 'medicines', 'created_by', 'media'])->get();

        return view('admin.ipdMedications.index', compact('ipdMedications'));
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_medication_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $medicines = Medicine::pluck('name', 'id');

        return view('admin.ipdMedications.create', compact('ipds', 'medicines'));
    }

    public function store(StoreIpdMedicationRequest $request)
    {
        $ipdMedication = IpdMedication::create($request->all());
        $ipdMedication->medicines()->sync($request->input('medicines', []));
        foreach ($request->input('attechment', []) as $file) {
            $ipdMedication->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdMedication->id]);
        }

        return redirect()->route('admin.ipd-medications.index');
    }

    public function edit(IpdMedication $ipdMedication)
    {
        abort_if(Gate::denies('ipd_medication_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $medicines = Medicine::pluck('name', 'id');

        $ipdMedication->load('ipd', 'medicines', 'created_by');

        return view('admin.ipdMedications.edit', compact('ipdMedication', 'ipds', 'medicines'));
    }

    public function update(UpdateIpdMedicationRequest $request, IpdMedication $ipdMedication)
    {
        $ipdMedication->update($request->all());
        $ipdMedication->medicines()->sync($request->input('medicines', []));
        if (count($ipdMedication->attechment) > 0) {
            foreach ($ipdMedication->attechment as $media) {
                if (! in_array($media->file_name, $request->input('attechment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ipdMedication->attechment->pluck('file_name')->toArray();
        foreach ($request->input('attechment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ipdMedication->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
            }
        }

        return redirect()->route('admin.ipd-medications.index');
    }

    public function show(IpdMedication $ipdMedication)
    {
        abort_if(Gate::denies('ipd_medication_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdMedication->load('ipd', 'medicines', 'created_by');

        return view('admin.ipdMedications.show', compact('ipdMedication'));
    }

    public function destroy(IpdMedication $ipdMedication)
    {
        abort_if(Gate::denies('ipd_medication_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdMedication->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdMedicationRequest $request)
    {
        $ipdMedications = IpdMedication::find(request('ids'));

        foreach ($ipdMedications as $ipdMedication) {
            $ipdMedication->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_medication_create') && Gate::denies('ipd_medication_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdMedication();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
