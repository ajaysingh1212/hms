<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreLabTestRequest;
use App\Http\Requests\UpdateLabTestRequest;
use App\Http\Resources\Admin\LabTestResource;
use App\Models\LabTest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabTestApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('lab_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LabTestResource(LabTest::with(['created_by'])->get());
    }

    public function store(StoreLabTestRequest $request)
    {
        $labTest = LabTest::create($request->all());

        return (new LabTestResource($labTest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LabTest $labTest)
    {
        abort_if(Gate::denies('lab_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LabTestResource($labTest->load(['created_by']));
    }

    public function update(UpdateLabTestRequest $request, LabTest $labTest)
    {
        $labTest->update($request->all());

        return (new LabTestResource($labTest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(LabTest $labTest)
    {
        abort_if(Gate::denies('lab_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $labTest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
