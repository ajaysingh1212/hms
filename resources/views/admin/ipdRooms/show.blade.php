@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ipdRoom.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-rooms.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.id') }}
                        </th>
                        <td>
                            {{ $ipdRoom->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.room_no') }}
                        </th>
                        <td>
                            {{ $ipdRoom->room_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.ward_type') }}
                        </th>
                        <td>
                            {{ App\Models\IpdRoom::WARD_TYPE_SELECT[$ipdRoom->ward_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.charges_per_day') }}
                        </th>
                        <td>
                            {{ $ipdRoom->charges_per_day }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.notes') }}
                        </th>
                        <td>
                            {!! $ipdRoom->notes !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\IpdRoom::STATUS_SELECT[$ipdRoom->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-rooms.index') }}">
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
            <a class="nav-link" href="#room_ipd_admissions" role="tab" data-toggle="tab">
                {{ trans('cruds.ipdAdmission.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="room_ipd_admissions">
            @includeIf('admin.ipdRooms.relationships.roomIpdAdmissions', ['ipdAdmissions' => $ipdRoom->roomIpdAdmissions])
        </div>
    </div>
</div>

@endsection