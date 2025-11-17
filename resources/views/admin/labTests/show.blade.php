@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.labTest.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lab-tests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.labTest.fields.id') }}
                        </th>
                        <td>
                            {{ $labTest->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.labTest.fields.test_name') }}
                        </th>
                        <td>
                            {{ $labTest->test_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.labTest.fields.price') }}
                        </th>
                        <td>
                            {{ $labTest->price }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.labTest.fields.description') }}
                        </th>
                        <td>
                            {!! $labTest->description !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lab-tests.index') }}">
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
            <a class="nav-link" href="#test_opd_tests" role="tab" data-toggle="tab">
                {{ trans('cruds.opdTest.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="test_opd_tests">
            @includeIf('admin.labTests.relationships.testOpdTests', ['opdTests' => $labTest->testOpdTests])
        </div>
    </div>
</div>

@endsection