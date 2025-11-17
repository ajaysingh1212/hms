<?php

namespace App\Http\Requests;

use App\Models\OpdBilling;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreOpdBillingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('opd_billing_create');
    }

    public function rules()
    {
        return [
            'attechment' => [
                'array',
            ],
        ];
    }
}
