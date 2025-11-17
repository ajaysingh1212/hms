<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreDepartmentNameRequest;
use App\Http\Requests\UpdateDepartmentNameRequest;
use App\Http\Resources\Admin\DepartmentNameResource;
use App\Models\DepartmentName;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartmentNameApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('department_name_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DepartmentNameResource(DepartmentName::with(['created_by'])->get());
    }

    public function store(StoreDepartmentNameRequest $request)
    {
        $departmentName = DepartmentName::create($request->all());

        return (new DepartmentNameResource($departmentName))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(DepartmentName $departmentName)
    {
        abort_if(Gate::denies('department_name_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DepartmentNameResource($departmentName->load(['created_by']));
    }

    public function update(UpdateDepartmentNameRequest $request, DepartmentName $departmentName)
    {
        $departmentName->update($request->all());

        return (new DepartmentNameResource($departmentName))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(DepartmentName $departmentName)
    {
        abort_if(Gate::denies('department_name_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departmentName->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
