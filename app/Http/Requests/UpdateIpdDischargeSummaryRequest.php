<?php

namespace App\Http\Requests;

use App\Models\IpdDischargeSummary;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateIpdDischargeSummaryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('ipd_discharge_summary_edit');
    }

    public function rules()
    {
        return [
            'discharge_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'condition_on_discharge' => [
                'string',
                'nullable',
            ],
            'followup_date' => [
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
        ];
    }
}
