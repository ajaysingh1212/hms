<?php

namespace App\Http\Requests;

use App\Models\OpdVisit;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyOpdVisitRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('opd_visit_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:opd_visits,id',
        ];
    }
}
