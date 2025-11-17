@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.appointment.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.appointments.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.id') }}
                        </th>
                        <td>
                            {{ $appointment->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.patient_number') }}
                        </th>
                        <td>
                            {{ $appointment->patient_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.department') }}
                        </th>
                        <td>
                            {{ $appointment->department->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.doctor') }}
                        </th>
                        <td>
                            {{ $appointment->doctor->doctor_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.available_slots') }}
                        </th>
                        <td>
                            {{ $appointment->available_slots->select_time ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.patient_name') }}
                        </th>
                        <td>
                            {{ $appointment->patient_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.mobile_number') }}
                        </th>
                        <td>
                            {{ $appointment->mobile_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.date') }}
                        </th>
                        <td>
                            {{ $appointment->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.reason_for_visit') }}
                        </th>
                        <td>
                            {!! $appointment->reason_for_visit !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.appointment_type') }}
                        </th>
                        <td>
                            {{ App\Models\Appointment::APPOINTMENT_TYPE_SELECT[$appointment->appointment_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.appointment.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Appointment::STATUS_SELECT[$appointment->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.appointments.index') }}">
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
            <a class="nav-link" href="#patient_opd_visits" role="tab" data-toggle="tab">
                {{ trans('cruds.opdVisit.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#patient_ipd_admissions" role="tab" data-toggle="tab">
                {{ trans('cruds.ipdAdmission.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="patient_opd_visits">
            @includeIf('admin.appointments.relationships.patientOpdVisits', ['opdVisits' => $appointment->patientOpdVisits])
        </div>
        <div class="tab-pane" role="tabpanel" id="patient_ipd_admissions">
            @includeIf('admin.appointments.relationships.patientIpdAdmissions', ['ipdAdmissions' => $appointment->patientIpdAdmissions])
        </div>
    </div>
</div>

@endsection