@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.medicine.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.medicines.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.medicine.fields.id') }}
                        </th>
                        <td>
                            {{ $medicine->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.medicine.fields.name') }}
                        </th>
                        <td>
                            {{ $medicine->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.medicine.fields.price') }}
                        </th>
                        <td>
                            {{ $medicine->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.medicine.fields.notes') }}
                        </th>
                        <td>
                            {!! $medicine->notes !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.medicines.index') }}">
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
            <a class="nav-link" href="#medicine_ipd_medications" role="tab" data-toggle="tab">
                {{ trans('cruds.ipdMedication.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#medicine_opd_prescriptions" role="tab" data-toggle="tab">
                {{ trans('cruds.opdPrescription.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="medicine_ipd_medications">
            @includeIf('admin.medicines.relationships.medicineIpdMedications', ['ipdMedications' => $medicine->medicineIpdMedications])
        </div>
        <div class="tab-pane" role="tabpanel" id="medicine_opd_prescriptions">
            @includeIf('admin.medicines.relationships.medicineOpdPrescriptions', ['opdPrescriptions' => $medicine->medicineOpdPrescriptions])
        </div>
    </div>
</div>

@endsection