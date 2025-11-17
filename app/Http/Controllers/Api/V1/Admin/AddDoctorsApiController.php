<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAddDoctorRequest;
use App\Http\Requests\UpdateAddDoctorRequest;
use App\Http\Resources\Admin\AddDoctorResource;
use App\Models\AddDoctor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddDoctorsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('add_doctor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddDoctorResource(AddDoctor::with(['select_department', 'created_by'])->get());
    }

    public function store(StoreAddDoctorRequest $request)
    {
        $addDoctor = AddDoctor::create($request->all());

        return (new AddDoctorResource($addDoctor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AddDoctor $addDoctor)
    {
        abort_if(Gate::denies('add_doctor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddDoctorResource($addDoctor->load(['select_department', 'created_by']));
    }

    public function update(UpdateAddDoctorRequest $request, AddDoctor $addDoctor)
    {
        $addDoctor->update($request->all());

        return (new AddDoctorResource($addDoctor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AddDoctor $addDoctor)
    {
        abort_if(Gate::denies('add_doctor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addDoctor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
