<?php

namespace App\Http\Requests;

use App\Models\IpdMedication;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateIpdMedicationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_medication_edit');
    }

    public function rules()
    {
        return [
            'medicines.*' => [
                'integer',
            ],
            'medicines' => [
                'array',
            ],
            'dosage' => [
                'string',
                'nullable',
            ],
            'frequency' => [
                'string',
                'nullable',
            ],
            'attechment' => [
                'array',
            ],
        ];
    }
}
