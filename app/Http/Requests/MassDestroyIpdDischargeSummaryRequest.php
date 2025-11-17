<?php

namespace App\Http\Requests;

use App\Models\IpdDischargeSummary;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyIpdDischargeSummaryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ipd_discharge_summary_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:ipd_discharge_summaries,id',
        ];
    }
}
