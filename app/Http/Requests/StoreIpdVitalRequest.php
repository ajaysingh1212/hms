<?php

namespace App\Http\Requests;

use App\Models\IpdVital;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreIpdVitalRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_vital_create');
    }

    public function rules()
    {
        return [
            'date_time' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'temperature' => [
                'string',
                'nullable',
            ],
            'pulse' => [
                'string',
                'nullable',
            ],
            'bp' => [
                'string',
                'nullable',
            ],
            'spo_2' => [
                'string',
                'nullable',
            ],
            'respiration' => [
                'string',
                'nullable',
            ],
        ];
    }
}
