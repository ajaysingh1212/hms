<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreIpdRoomRequest;
use App\Http\Requests\UpdateIpdRoomRequest;
use App\Http\Resources\Admin\IpdRoomResource;
use App\Models\IpdRoom;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpdRoomsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_room_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdRoomResource(IpdRoom::with(['created_by'])->get());
    }

    public function store(StoreIpdRoomRequest $request)
    {
        $ipdRoom = IpdRoom::create($request->all());

        return (new IpdRoomResource($ipdRoom))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(IpdRoom $ipdRoom)
    {
        abort_if(Gate::denies('ipd_room_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new IpdRoomResource($ipdRoom->load(['created_by']));
    }

    public function update(UpdateIpdRoomRequest $request, IpdRoom $ipdRoom)
    {
        $ipdRoom->update($request->all());

        return (new IpdRoomResource($ipdRoom))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(IpdRoom $ipdRoom)
    {
        abort_if(Gate::denies('ipd_room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdRoom->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
