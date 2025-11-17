<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdDischargeSummaryRequest;
use App\Http\Requests\UpdateIpdDischargeSummaryRequest;
use App\Http\Resources\Admin\IpdDischargeSummaryResource;
use App\Models\IpdDischargeSummary;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdDischargeSummaryApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_discharge_summary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdDischargeSummaryResource(IpdDischargeSummary::with(['ipd', 'created_by'])->get());
    }

    public function store(StoreIpdDischargeSummaryRequest $request)
    {
        $ipdDischargeSummary = IpdDischargeSummary::create($request->all());

        if ($request->input('attechments', false)) {
            $ipdDischargeSummary->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
        }

        return (new IpdDischargeSummaryResource($ipdDischargeSummary))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdDischargeSummary $ipdDischargeSummary)
    {
        abort_if(Gate::denies('ipd_discharge_summary_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdDischargeSummaryResource($ipdDischargeSummary->load(['ipd', 'created_by']));
    }

    public function update(UpdateIpdDischargeSummaryRequest $request, IpdDischargeSummary $ipdDischargeSummary)
    {
        $ipdDischargeSummary->update($request->all());

        if ($request->input('attechments', false)) {
            if (! $ipdDischargeSummary->attechments || $request->input('attechments') !== $ipdDischargeSummary->attechments->file_name) {
                if ($ipdDischargeSummary->attechments) {
                    $ipdDischargeSummary->attechments->delete();
                }
                $ipdDischargeSummary->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
            }
        } elseif ($ipdDischargeSummary->attechments) {
            $ipdDischargeSummary->attechments->delete();
        }

        return (new IpdDischargeSummaryResource($ipdDischargeSummary))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdDischargeSummary $ipdDischargeSummary)
    {
        abort_if(Gate::denies('ipd_discharge_summary_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdDischargeSummary->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
