<?php

namespace App\Http\Requests;

use App\Models\OpdBilling;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOpdBillingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('opd_billing_edit');
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
