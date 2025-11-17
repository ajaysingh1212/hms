@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ipdAdmission.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-admissions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.id') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.patient') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->patient->patient_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.doctor') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->doctor->doctor_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_date') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->admission_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_time') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->admission_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.room') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->room->room_no ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.bed') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->bed->bed_no ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.condition_on_admission') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->condition_on_admission }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.reason') }}
                        </th>
                        <td>
                            {!! $ipdAdmission->reason !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\IpdAdmission::STATUS_SELECT[$ipdAdmission->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.attechment') }}
                        </th>
                        <td>
                            @if($ipdAdmission->attechment)
                                <a href="{{ $ipdAdmission->attechment->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.ipd_number') }}
                        </th>
                        <td>
                            {{ $ipdAdmission->ipd_number }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-admissions.index') }}">
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
            <a class="nav-link" href="#ipd_ipd_medications" role="tab" data-toggle="tab">
                {{ trans('cruds.ipdMedication.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#ipd_ipd_vitals" role="tab" data-toggle="tab">
                {{ trans('cruds.ipdVital.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#ipd_ipd_tests" role="tab" data-toggle="tab">
                {{ trans('cruds.ipdTest.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="ipd_ipd_medications">
            @includeIf('admin.ipdAdmissions.relationships.ipdIpdMedications', ['ipdMedications' => $ipdAdmission->ipdIpdMedications])
        </div>
        <div class="tab-pane" role="tabpanel" id="ipd_ipd_vitals">
            @includeIf('admin.ipdAdmissions.relationships.ipdIpdVitals', ['ipdVitals' => $ipdAdmission->ipdIpdVitals])
        </div>
        <div class="tab-pane" role="tabpanel" id="ipd_ipd_tests">
            @includeIf('admin.ipdAdmissions.relationships.ipdIpdTests', ['ipdTests' => $ipdAdmission->ipdIpdTests])
        </div>
    </div>
</div>

@endsection