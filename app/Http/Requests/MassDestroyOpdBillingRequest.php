<?php

namespace App\Http\Requests;

use App\Models\OpdBilling;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOpdBillingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('opd_billing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:opd_billings,id',
        ];
    }
}
