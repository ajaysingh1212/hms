<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdAdmissionRequest;
use App\Http\Requests\UpdateIpdAdmissionRequest;
use App\Http\Resources\Admin\IpdAdmissionResource;
use App\Models\IpdAdmission;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdAdmissionsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_admission_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdAdmissionResource(IpdAdmission::with(['patient', 'doctor', 'room', 'bed', 'created_by'])->get());
    }

    public function store(StoreIpdAdmissionRequest $request)
    {
        $ipdAdmission = IpdAdmission::create($request->all());

        if ($request->input('attechment', false)) {
            $ipdAdmission->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechment'))))->toMediaCollection('attechment');
        }

        return (new IpdAdmissionResource($ipdAdmission))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdAdmission $ipdAdmission)
    {
        abort_if(Gate::denies('ipd_admission_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdAdmissionResource($ipdAdmission->load(['patient', 'doctor', 'room', 'bed', 'created_by']));
    }

    public function update(UpdateIpdAdmissionRequest $request, IpdAdmission $ipdAdmission)
    {
        $ipdAdmission->update($request->all());

        if ($request->input('attechment', false)) {
            if (! $ipdAdmission->attechment || $request->input('attechment') !== $ipdAdmission->attechment->file_name) {
                if ($ipdAdmission->attechment) {
                    $ipdAdmission->attechment->delete();
                }
                $ipdAdmission->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechment'))))->toMediaCollection('attechment');
            }
        } elseif ($ipdAdmission->attechment) {
            $ipdAdmission->attechment->delete();
        }

        return (new IpdAdmissionResource($ipdAdmission))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdAdmission $ipdAdmission)
    {
        abort_if(Gate::denies('ipd_admission_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdAdmission->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
