<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdMedicationRequest;
use App\Http\Requests\UpdateIpdMedicationRequest;
use App\Http\Resources\Admin\IpdMedicationResource;
use App\Models\IpdMedication;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdMedicationsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_medication_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdMedicationResource(IpdMedication::with(['ipd', 'medicines', 'created_by'])->get());
    }

    public function store(StoreIpdMedicationRequest $request)
    {
        $ipdMedication = IpdMedication::create($request->all());
        $ipdMedication->medicines()->sync($request->input('medicines', []));
        foreach ($request->input('attechment', []) as $file) {
            $ipdMedication->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
        }

        return (new IpdMedicationResource($ipdMedication))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdMedication $ipdMedication)
    {
        abort_if(Gate::denies('ipd_medication_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdMedicationResource($ipdMedication->load(['ipd', 'medicines', 'created_by']));
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

        return (new IpdMedicationResource($ipdMedication))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdMedication $ipdMedication)
    {
        abort_if(Gate::denies('ipd_medication_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdMedication->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
