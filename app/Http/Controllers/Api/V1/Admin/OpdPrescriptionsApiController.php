<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreOpdPrescriptionRequest;
use App\Http\Requests\UpdateOpdPrescriptionRequest;
use App\Http\Resources\Admin\OpdPrescriptionResource;
use App\Models\OpdPrescription;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OpdPrescriptionsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_prescription_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OpdPrescriptionResource(OpdPrescription::with(['opd', 'medicines', 'created_by'])->get());
    }

    public function store(StoreOpdPrescriptionRequest $request)
    {
        $opdPrescription = OpdPrescription::create($request->all());
        $opdPrescription->medicines()->sync($request->input('medicines', []));
        foreach ($request->input('attechment', []) as $file) {
            $opdPrescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
        }

        return (new OpdPrescriptionResource($opdPrescription))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OpdPrescription $opdPrescription)
    {
        abort_if(Gate::denies('opd_prescription_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OpdPrescriptionResource($opdPrescription->load(['opd', 'medicines', 'created_by']));
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

        return (new OpdPrescriptionResource($opdPrescription))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OpdPrescription $opdPrescription)
    {
        abort_if(Gate::denies('opd_prescription_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdPrescription->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
