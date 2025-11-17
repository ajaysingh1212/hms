<?php

namespace App\Http\Requests;

use App\Models\IpdBed;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreIpdBedRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_bed_create');
    }

    public function rules()
    {
        return [
            'room_id' => [
                'required',
                'integer',
            ],
            'bed_no' => [
                'string',
                'nullable',
            ],
        ];
    }
}
