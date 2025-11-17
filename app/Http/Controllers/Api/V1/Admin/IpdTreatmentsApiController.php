<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdTreatmentRequest;
use App\Http\Requests\UpdateIpdTreatmentRequest;
use App\Http\Resources\Admin\IpdTreatmentResource;
use App\Models\IpdTreatment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdTreatmentsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_treatment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdTreatmentResource(IpdTreatment::with(['ipd', 'created_by'])->get());
    }

    public function store(StoreIpdTreatmentRequest $request)
    {
        $ipdTreatment = IpdTreatment::create($request->all());

        if ($request->input('attechment', false)) {
            $ipdTreatment->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechment'))))->toMediaCollection('attechment');
        }

        return (new IpdTreatmentResource($ipdTreatment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdTreatment $ipdTreatment)
    {
        abort_if(Gate::denies('ipd_treatment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdTreatmentResource($ipdTreatment->load(['ipd', 'created_by']));
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

        return (new IpdTreatmentResource($ipdTreatment))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdTreatment $ipdTreatment)
    {
        abort_if(Gate::denies('ipd_treatment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTreatment->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
