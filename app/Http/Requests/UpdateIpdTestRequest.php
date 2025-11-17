<?php

namespace App\Http\Requests;

use App\Models\IpdTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateIpdTestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_test_edit');
    }

    public function rules()
    {
        return [
            'tests.*' => [
                'integer',
            ],
            'tests' => [
                'array',
            ],
            'attechments' => [
                'array',
            ],
        ];
    }
}
