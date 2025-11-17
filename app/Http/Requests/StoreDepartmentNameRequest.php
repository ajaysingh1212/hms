<?php

namespace App\Http\Requests;

use App\Models\DepartmentName;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreDepartmentNameRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('department_name_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
