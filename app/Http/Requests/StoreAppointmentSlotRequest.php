<?php

namespace App\Http\Requests;

use App\Models\AppointmentSlot;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAppointmentSlotRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('appointment_slot_create');
    }

    public function rules()
    {
        return [
            'select_doctor_id' => [
                'required',
                'integer',
            ],
            'select_time' => [
                'required',
                'date_format:' . config('panel.time_format'),
            ],
        ];
    }
}
