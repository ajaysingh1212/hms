<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdVitalRequest;
use App\Http\Requests\UpdateIpdVitalRequest;
use App\Http\Resources\Admin\IpdVitalResource;
use App\Models\IpdVital;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdVitalsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_vital_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdVitalResource(IpdVital::with(['ipd', 'created_by'])->get());
    }

    public function store(StoreIpdVitalRequest $request)
    {
        $ipdVital = IpdVital::create($request->all());

        if ($request->input('attechments', false)) {
            $ipdVital->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
        }

        return (new IpdVitalResource($ipdVital))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdVital $ipdVital)
    {
        abort_if(Gate::denies('ipd_vital_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdVitalResource($ipdVital->load(['ipd', 'created_by']));
    }

    public function update(UpdateIpdVitalRequest $request, IpdVital $ipdVital)
    {
        $ipdVital->update($request->all());

        if ($request->input('attechments', false)) {
            if (! $ipdVital->attechments || $request->input('attechments') !== $ipdVital->attechments->file_name) {
                if ($ipdVital->attechments) {
                    $ipdVital->attechments->delete();
                }
                $ipdVital->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
            }
        } elseif ($ipdVital->attechments) {
            $ipdVital->attechments->delete();
        }

        return (new IpdVitalResource($ipdVital))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdVital $ipdVital)
    {
        abort_if(Gate::denies('ipd_vital_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdVital->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
