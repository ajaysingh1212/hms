<?php

namespace App\Http\Requests;

use App\Models\IpdMedication;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreIpdMedicationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_medication_create');
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
