<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdTestRequest;
use App\Http\Requests\UpdateIpdTestRequest;
use App\Http\Resources\Admin\IpdTestResource;
use App\Models\IpdTest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdTestApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdTestResource(IpdTest::with(['ipd', 'tests', 'created_by'])->get());
    }

    public function store(StoreIpdTestRequest $request)
    {
        $ipdTest = IpdTest::create($request->all());
        $ipdTest->tests()->sync($request->input('tests', []));
        foreach ($request->input('attechments', []) as $file) {
            $ipdTest->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
        }

        return (new IpdTestResource($ipdTest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdTest $ipdTest)
    {
        abort_if(Gate::denies('ipd_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdTestResource($ipdTest->load(['ipd', 'tests', 'created_by']));
    }

    public function update(UpdateIpdTestRequest $request, IpdTest $ipdTest)
    {
        $ipdTest->update($request->all());
        $ipdTest->tests()->sync($request->input('tests', []));
        if (count($ipdTest->attechments) > 0) {
            foreach ($ipdTest->attechments as $media) {
                if (! in_array($media->file_name, $request->input('attechments', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ipdTest->attechments->pluck('file_name')->toArray();
        foreach ($request->input('attechments', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ipdTest->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
            }
        }

        return (new IpdTestResource($ipdTest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdTest $ipdTest)
    {
        abort_if(Gate::denies('ipd_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
