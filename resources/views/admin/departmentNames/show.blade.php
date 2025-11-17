@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.departmentName.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.department-names.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.departmentName.fields.id') }}
                        </th>
                        <td>
                            {{ $departmentName->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.departmentName.fields.name') }}
                        </th>
                        <td>
                            {{ $departmentName->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.departmentName.fields.notes') }}
                        </th>
                        <td>
                            {!! $departmentName->notes !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.department-names.index') }}">
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
            <a class="nav-link" href="#select_department_add_doctors" role="tab" data-toggle="tab">
                {{ trans('cruds.addDoctor.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="select_department_add_doctors">
            @includeIf('admin.departmentNames.relationships.selectDepartmentAddDoctors', ['addDoctors' => $departmentName->selectDepartmentAddDoctors])
        </div>
    </div>
</div>

@endsection