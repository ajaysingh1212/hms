<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdRoomRequest;
use App\Http\Requests\StoreIpdRoomRequest;
use App\Http\Requests\UpdateIpdRoomRequest;
use App\Models\IpdRoom;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdRoomsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_room_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdRooms = IpdRoom::with(['created_by'])->get();

        return view('admin.ipdRooms.index', compact('ipdRooms'));
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_room_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ipdRooms.create');
    }

    public function store(StoreIpdRoomRequest $request)
    {
        $ipdRoom = IpdRoom::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdRoom->id]);
        }

        return redirect()->route('admin.ipd-rooms.index');
    }

    public function edit(IpdRoom $ipdRoom)
    {
        abort_if(Gate::denies('ipd_room_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdRoom->load('created_by');

        return view('admin.ipdRooms.edit', compact('ipdRoom'));
    }

    public function update(UpdateIpdRoomRequest $request, IpdRoom $ipdRoom)
    {
        $ipdRoom->update($request->all());

        return redirect()->route('admin.ipd-rooms.index');
    }

    public function show(IpdRoom $ipdRoom)
    {
        abort_if(Gate::denies('ipd_room_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdRoom->load('created_by', 'roomIpdAdmissions');

        return view('admin.ipdRooms.show', compact('ipdRoom'));
    }

    public function destroy(IpdRoom $ipdRoom)
    {
        abort_if(Gate::denies('ipd_room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdRoom->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdRoomRequest $request)
    {
        $ipdRooms = IpdRoom::find(request('ids'));

        foreach ($ipdRooms as $ipdRoom) {
            $ipdRoom->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_room_create') && Gate::denies('ipd_room_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdRoom();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
