@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ipdDischargeSummary.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-discharge-summaries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.id') }}
                        </th>
                        <td>
                            {{ $ipdDischargeSummary->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.ipd') }}
                        </th>
                        <td>
                            {{ $ipdDischargeSummary->ipd->ipd_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.discharge_date') }}
                        </th>
                        <td>
                            {{ $ipdDischargeSummary->discharge_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.condition_on_discharge') }}
                        </th>
                        <td>
                            {{ $ipdDischargeSummary->condition_on_discharge }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.summary') }}
                        </th>
                        <td>
                            {!! $ipdDischargeSummary->summary !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.advice') }}
                        </th>
                        <td>
                            {!! $ipdDischargeSummary->advice !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.followup_date') }}
                        </th>
                        <td>
                            {{ $ipdDischargeSummary->followup_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.attechments') }}
                        </th>
                        <td>
                            @if($ipdDischargeSummary->attechments)
                                <a href="{{ $ipdDischargeSummary->attechments->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-discharge-summaries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection