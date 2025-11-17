<?php

namespace App\Http\Requests;

use App\Models\LabTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyLabTestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('lab_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:lab_tests,id',
        ];
    }
}
