@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.opdPrescription.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opd-prescriptions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.id') }}
                        </th>
                        <td>
                            {{ $opdPrescription->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.opd') }}
                        </th>
                        <td>
                            {{ $opdPrescription->opd->visit_date ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.medicine') }}
                        </th>
                        <td>
                            @foreach($opdPrescription->medicines as $key => $medicine)
                                <span class="label label-info">{{ $medicine->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.dosage') }}
                        </th>
                        <td>
                            {{ $opdPrescription->dosage }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.duration') }}
                        </th>
                        <td>
                            {{ $opdPrescription->duration }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.instructions') }}
                        </th>
                        <td>
                            {!! $opdPrescription->instructions !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.attechment') }}
                        </th>
                        <td>
                            @foreach($opdPrescription->attechment as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opd-prescriptions.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection