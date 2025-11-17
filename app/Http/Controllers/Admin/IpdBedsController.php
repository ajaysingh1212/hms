<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdBedRequest;
use App\Http\Requests\StoreIpdBedRequest;
use App\Http\Requests\UpdateIpdBedRequest;
use App\Models\IpdBed;
use App\Models\IpdRoom;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdBedsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_bed_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBeds = IpdBed::with(['room', 'created_by'])->get();

        return view('admin.ipdBeds.index', compact('ipdBeds'));
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_bed_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = IpdRoom::pluck('room_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdBeds.create', compact('rooms'));
    }

    public function store(StoreIpdBedRequest $request)
    {
        $ipdBed = IpdBed::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdBed->id]);
        }

        return redirect()->route('admin.ipd-beds.index');
    }

    public function edit(IpdBed $ipdBed)
    {
        abort_if(Gate::denies('ipd_bed_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = IpdRoom::pluck('room_no', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdBed->load('room', 'created_by');

        return view('admin.ipdBeds.edit', compact('ipdBed', 'rooms'));
    }

    public function update(UpdateIpdBedRequest $request, IpdBed $ipdBed)
    {
        $ipdBed->update($request->all());

        return redirect()->route('admin.ipd-beds.index');
    }

    public function show(IpdBed $ipdBed)
    {
        abort_if(Gate::denies('ipd_bed_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBed->load('room', 'created_by');

        return view('admin.ipdBeds.show', compact('ipdBed'));
    }

    public function destroy(IpdBed $ipdBed)
    {
        abort_if(Gate::denies('ipd_bed_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBed->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdBedRequest $request)
    {
        $ipdBeds = IpdBed::find(request('ids'));

        foreach ($ipdBeds as $ipdBed) {
            $ipdBed->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_bed_create') && Gate::denies('ipd_bed_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdBed();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
