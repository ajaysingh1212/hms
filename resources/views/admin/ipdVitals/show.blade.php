@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ipdVital.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-vitals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.id') }}
                        </th>
                        <td>
                            {{ $ipdVital->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.ipd') }}
                        </th>
                        <td>
                            {{ $ipdVital->ipd->ipd_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.date_time') }}
                        </th>
                        <td>
                            {{ $ipdVital->date_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.temperature') }}
                        </th>
                        <td>
                            {{ $ipdVital->temperature }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.pulse') }}
                        </th>
                        <td>
                            {{ $ipdVital->pulse }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.bp') }}
                        </th>
                        <td>
                            {{ $ipdVital->bp }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.spo_2') }}
                        </th>
                        <td>
                            {{ $ipdVital->spo_2 }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.respiration') }}
                        </th>
                        <td>
                            {{ $ipdVital->respiration }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.notes') }}
                        </th>
                        <td>
                            {!! $ipdVital->notes !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdVital.fields.attechments') }}
                        </th>
                        <td>
                            @if($ipdVital->attechments)
                                <a href="{{ $ipdVital->attechments->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-vitals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection