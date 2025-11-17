<?php

namespace App\Http\Requests;

use App\Models\IpdTreatment;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyIpdTreatmentRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ipd_treatment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:ipd_treatments,id',
        ];
    }
}
