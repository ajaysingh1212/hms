<?php

namespace App\Http\Requests;

use App\Models\OpdPrescription;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOpdPrescriptionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('opd_prescription_edit');
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
            'duration' => [
                'string',
                'nullable',
            ],
            'attechment' => [
                'array',
            ],
        ];
    }
}
