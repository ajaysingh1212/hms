<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreOpdVisitRequest;
use App\Http\Requests\UpdateOpdVisitRequest;
use App\Http\Resources\Admin\OpdVisitResource;
use App\Models\OpdVisit;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OpdVisitsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_visit_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OpdVisitResource(OpdVisit::with(['patient', 'doctor', 'created_by'])->get());
    }

    public function store(StoreOpdVisitRequest $request)
    {
        $opdVisit = OpdVisit::create($request->all());

        foreach ($request->input('attechment', []) as $file) {
            $opdVisit->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
        }

        return (new OpdVisitResource($opdVisit))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OpdVisit $opdVisit)
    {
        abort_if(Gate::denies('opd_visit_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OpdVisitResource($opdVisit->load(['patient', 'doctor', 'created_by']));
    }

    public function update(UpdateOpdVisitRequest $request, OpdVisit $opdVisit)
    {
        $opdVisit->update($request->all());

        if (count($opdVisit->attechment) > 0) {
            foreach ($opdVisit->attechment as $media) {
                if (! in_array($media->file_name, $request->input('attechment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $opdVisit->attechment->pluck('file_name')->toArray();
        foreach ($request->input('attechment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $opdVisit->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
            }
        }

        return (new OpdVisitResource($opdVisit))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OpdVisit $opdVisit)
    {
        abort_if(Gate::denies('opd_visit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdVisit->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
