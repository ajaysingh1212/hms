@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ipdTreatment.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-treatments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.id') }}
                        </th>
                        <td>
                            {{ $ipdTreatment->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.ipd') }}
                        </th>
                        <td>
                            {{ $ipdTreatment->ipd->ipd_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.date') }}
                        </th>
                        <td>
                            {{ $ipdTreatment->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.doctor_notes') }}
                        </th>
                        <td>
                            {!! $ipdTreatment->doctor_notes !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.diagnosis') }}
                        </th>
                        <td>
                            {!! $ipdTreatment->diagnosis !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.treatment') }}
                        </th>
                        <td>
                            {!! $ipdTreatment->treatment !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.attechment') }}
                        </th>
                        <td>
                            @if($ipdTreatment->attechment)
                                <a href="{{ $ipdTreatment->attechment->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-treatments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection