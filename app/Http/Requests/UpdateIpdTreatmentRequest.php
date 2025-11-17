<?php

namespace App\Http\Requests;

use App\Models\IpdTreatment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateIpdTreatmentRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_treatment_edit');
    }

    public function rules()
    {
        return [
            'date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
