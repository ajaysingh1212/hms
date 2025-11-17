<?php

namespace App\Http\Requests;

use App\Models\LabTest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLabTestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lab_test_edit');
    }

    public function rules()
    {
        return [
            'test_name' => [
                'string',
                'required',
            ],
            'price' => [
                'required',
            ],
        ];
    }
}
