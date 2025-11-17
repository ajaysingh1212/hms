<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdBillingRequest;
use App\Http\Requests\UpdateIpdBillingRequest;
use App\Http\Resources\Admin\IpdBillingResource;
use App\Models\IpdBilling;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdBillingApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_billing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdBillingResource(IpdBilling::with(['ipd', 'created_by'])->get());
    }

    public function store(StoreIpdBillingRequest $request)
    {
        $ipdBilling = IpdBilling::create($request->all());

        foreach ($request->input('attechments', []) as $file) {
            $ipdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
        }

        return (new IpdBillingResource($ipdBilling))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdBilling $ipdBilling)
    {
        abort_if(Gate::denies('ipd_billing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdBillingResource($ipdBilling->load(['ipd', 'created_by']));
    }

    public function update(UpdateIpdBillingRequest $request, IpdBilling $ipdBilling)
    {
        $ipdBilling->update($request->all());

        if (count($ipdBilling->attechments) > 0) {
            foreach ($ipdBilling->attechments as $media) {
                if (! in_array($media->file_name, $request->input('attechments', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ipdBilling->attechments->pluck('file_name')->toArray();
        foreach ($request->input('attechments', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ipdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
            }
        }

        return (new IpdBillingResource($ipdBilling))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdBilling $ipdBilling)
    {
        abort_if(Gate::denies('ipd_billing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBilling->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
