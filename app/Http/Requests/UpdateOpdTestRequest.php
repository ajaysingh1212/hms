<?php

namespace App\Http\Requests;

use App\Models\OpdTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOpdTestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('opd_test_edit');
    }

    public function rules()
    {
        return [
            'opd_id' => [
                'required',
                'integer',
            ],
            'tests.*' => [
                'integer',
            ],
            'tests' => [
                'array',
            ],
        ];
    }
}
