<?php

namespace App\Http\Requests;

use App\Models\IpdAdmission;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreIpdAdmissionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_admission_create');
    }

    public function rules()
    {
        return [
            'patient_id' => [
                'required',
                'integer',
            ],
            'admission_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'admission_time' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'condition_on_admission' => [
                'string',
                'nullable',
            ],
            'ipd_number' => [
                'string',
                'nullable',
            ],
        ];
    }
}
