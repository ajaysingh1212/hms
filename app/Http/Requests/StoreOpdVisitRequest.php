<?php

namespace App\Http\Requests;

use App\Models\OpdVisit;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOpdVisitRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('opd_visit_create');
    }

    public function rules()
    {
        return [
            'patient_id' => [
                'required',
                'integer',
            ],
            'doctor_id' => [
                'required',
                'integer',
            ],
            'visit_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'visit_time' => [
                'date_format:' . config('panel.time_format'),
                'nullable',
            ],
            'attechment' => [
                'array',
            ],
        ];
    }
}
