<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdBedRequest;
use App\Http\Requests\UpdateIpdBedRequest;
use App\Http\Resources\Admin\IpdBedResource;
use App\Models\IpdBed;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdBedsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_bed_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdBedResource(IpdBed::with(['room', 'created_by'])->get());
    }

    public function store(StoreIpdBedRequest $request)
    {
        $ipdBed = IpdBed::create($request->all());

        return (new IpdBedResource($ipdBed))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdBed $ipdBed)
    {
        abort_if(Gate::denies('ipd_bed_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdBedResource($ipdBed->load(['room', 'created_by']));
    }

    public function update(UpdateIpdBedRequest $request, IpdBed $ipdBed)
    {
        $ipdBed->update($request->all());

        return (new IpdBedResource($ipdBed))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdBed $ipdBed)
    {
        abort_if(Gate::denies('ipd_bed_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBed->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
