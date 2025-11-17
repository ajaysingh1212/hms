<?php

namespace App\Http\Requests;

use App\Models\IpdRoom;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateIpdRoomRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_room_edit');
    }

    public function rules()
    {
        return [
            'room_no' => [
                'string',
                'required',
            ],
        ];
    }
}
