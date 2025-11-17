<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreOpdTestRequest;
use App\Http\Requests\UpdateOpdTestRequest;
use App\Http\Resources\Admin\OpdTestResource;
use App\Models\OpdTest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OpdTestsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OpdTestResource(OpdTest::with(['opd', 'tests', 'created_by'])->get());
    }

    public function store(StoreOpdTestRequest $request)
    {
        $opdTest = OpdTest::create($request->all());
        $opdTest->tests()->sync($request->input('tests', []));

        return (new OpdTestResource($opdTest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OpdTest $opdTest)
    {
        abort_if(Gate::denies('opd_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OpdTestResource($opdTest->load(['opd', 'tests', 'created_by']));
    }

    public function update(UpdateOpdTestRequest $request, OpdTest $opdTest)
    {
        $opdTest->update($request->all());
        $opdTest->tests()->sync($request->input('tests', []));

        return (new OpdTestResource($opdTest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OpdTest $opdTest)
    {
        abort_if(Gate::denies('opd_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdTest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
