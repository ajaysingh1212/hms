<?php

namespace App\Http\Requests;

use App\Models\AddDoctor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAddDoctorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('add_doctor_create');
    }

    public function rules()
    {
        return [
            'doctor_name' => [
                'string',
                'required',
            ],
            'appointment_slot_duration' => [
                'string',
                'nullable',
            ],
            'max_patients_per_day' => [
                'string',
                'nullable',
            ],
        ];
    }
}
