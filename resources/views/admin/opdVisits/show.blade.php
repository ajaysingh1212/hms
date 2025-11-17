@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.opdVisit.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opd-visits.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.id') }}
                        </th>
                        <td>
                            {{ $opdVisit->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.patient') }}
                        </th>
                        <td>
                            {{ $opdVisit->patient->patient_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.doctor') }}
                        </th>
                        <td>
                            {{ $opdVisit->doctor->doctor_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.visit_date') }}
                        </th>
                        <td>
                            {{ $opdVisit->visit_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.visit_time') }}
                        </th>
                        <td>
                            {{ $opdVisit->visit_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.symptoms') }}
                        </th>
                        <td>
                            {!! $opdVisit->symptoms !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.diagnosis') }}
                        </th>
                        <td>
                            {!! $opdVisit->diagnosis !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.notes') }}
                        </th>
                        <td>
                            {!! $opdVisit->notes !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.visit_type') }}
                        </th>
                        <td>
                            {{ App\Models\OpdVisit::VISIT_TYPE_SELECT[$opdVisit->visit_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\OpdVisit::STATUS_SELECT[$opdVisit->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdVisit.fields.attechment') }}
                        </th>
                        <td>
                            @foreach($opdVisit->attechment as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opd-visits.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection