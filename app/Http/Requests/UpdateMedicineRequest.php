<?php

namespace App\Http\Requests;

use App\Models\Medicine;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateMedicineRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('medicine_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'price' => [
                'string',
                'nullable',
            ],
        ];
    }
}
