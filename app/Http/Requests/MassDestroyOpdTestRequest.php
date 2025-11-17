<?php

namespace App\Http\Requests;

use App\Models\OpdTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOpdTestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('opd_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:opd_tests,id',
        ];
    }
}
