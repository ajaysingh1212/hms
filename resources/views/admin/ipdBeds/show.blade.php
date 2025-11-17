@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ipdBed.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-beds.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBed.fields.id') }}
                        </th>
                        <td>
                            {{ $ipdBed->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBed.fields.room') }}
                        </th>
                        <td>
                            {{ $ipdBed->room->room_no ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBed.fields.bed_no') }}
                        </th>
                        <td>
                            {{ $ipdBed->bed_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBed.fields.charges_per_day') }}
                        </th>
                        <td>
                            {{ $ipdBed->charges_per_day }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBed.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\IpdBed::STATUS_SELECT[$ipdBed->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBed.fields.notes') }}
                        </th>
                        <td>
                            {!! $ipdBed->notes !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-beds.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection