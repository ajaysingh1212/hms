@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.addDoctor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.add-doctors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.addDoctor.fields.id') }}
                        </th>
                        <td>
                            {{ $addDoctor->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addDoctor.fields.select_department') }}
                        </th>
                        <td>
                            {{ $addDoctor->select_department->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addDoctor.fields.doctor_name') }}
                        </th>
                        <td>
                            {{ $addDoctor->doctor_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addDoctor.fields.available_days') }}
                        </th>
                        <td>
                            {{ App\Models\AddDoctor::AVAILABLE_DAYS_RADIO[$addDoctor->available_days] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addDoctor.fields.appointment_slot_duration') }}
                        </th>
                        <td>
                            {{ $addDoctor->appointment_slot_duration }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addDoctor.fields.max_patients_per_day') }}
                        </th>
                        <td>
                            {{ $addDoctor->max_patients_per_day }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addDoctor.fields.doctor_fee') }}
                        </th>
                        <td>
                            {{ $addDoctor->doctor_fee }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.addDoctor.fields.description') }}
                        </th>
                        <td>
                            {!! $addDoctor->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.add-doctors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#doctor_opd_visits" role="tab" data-toggle="tab">
                {{ trans('cruds.opdVisit.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="doctor_opd_visits">
            @includeIf('admin.addDoctors.relationships.doctorOpdVisits', ['opdVisits' => $addDoctor->doctorOpdVisits])
        </div>
    </div>
</div>

@endsection