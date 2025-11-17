@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ipdMedication.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-medications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.id') }}
                        </th>
                        <td>
                            {{ $ipdMedication->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.ipd') }}
                        </th>
                        <td>
                            {{ $ipdMedication->ipd->ipd_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.medicine') }}
                        </th>
                        <td>
                            @foreach($ipdMedication->medicines as $key => $medicine)
                                <span class="label label-info">{{ $medicine->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.dosage') }}
                        </th>
                        <td>
                            {{ $ipdMedication->dosage }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.frequency') }}
                        </th>
                        <td>
                            {{ $ipdMedication->frequency }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.route') }}
                        </th>
                        <td>
                            {{ App\Models\IpdMedication::ROUTE_SELECT[$ipdMedication->route] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.notes') }}
                        </th>
                        <td>
                            {!! $ipdMedication->notes !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.attechment') }}
                        </th>
                        <td>
                            @foreach($ipdMedication->attechment as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-medications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection