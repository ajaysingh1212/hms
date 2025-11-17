<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreOpdBillingRequest;
use App\Http\Requests\UpdateOpdBillingRequest;
use App\Http\Resources\Admin\OpdBillingResource;
use App\Models\OpdBilling;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OpdBillingApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_billing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OpdBillingResource(OpdBilling::with(['opd', 'created_by'])->get());
    }

    public function store(StoreOpdBillingRequest $request)
    {
        $opdBilling = OpdBilling::create($request->all());

        foreach ($request->input('attechment', []) as $file) {
            $opdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
        }

        return (new OpdBillingResource($opdBilling))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OpdBilling $opdBilling)
    {
        abort_if(Gate::denies('opd_billing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OpdBillingResource($opdBilling->load(['opd', 'created_by']));
    }

    public function update(UpdateOpdBillingRequest $request, OpdBilling $opdBilling)
    {
        $opdBilling->update($request->all());

        if (count($opdBilling->attechment) > 0) {
            foreach ($opdBilling->attechment as $media) {
                if (! in_array($media->file_name, $request->input('attechment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $opdBilling->attechment->pluck('file_name')->toArray();
        foreach ($request->input('attechment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $opdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
            }
        }

        return (new OpdBillingResource($opdBilling))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OpdBilling $opdBilling)
    {
        abort_if(Gate::denies('opd_billing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdBilling->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
