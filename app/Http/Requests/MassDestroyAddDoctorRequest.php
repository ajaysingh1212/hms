<?php

namespace App\Http\Requests;

use App\Models\AddDoctor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAddDoctorRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('add_doctor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:add_doctors,id',
        ];
    }
}
