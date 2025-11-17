<?php

namespace App\Http\Requests;

use App\Models\IpdBilling;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreIpdBillingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_billing_create');
    }

    public function rules()
    {
        return [
            'notes' => [
                'required',
            ],
            'attechments' => [
                'array',
            ],
        ];
    }
}
