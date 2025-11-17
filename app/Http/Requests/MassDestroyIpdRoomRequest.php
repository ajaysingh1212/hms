<?php

namespace App\Http\Requests;

use App\Models\IpdRoom;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyIpdRoomRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ipd_room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:ipd_rooms,id',
        ];
    }
}
