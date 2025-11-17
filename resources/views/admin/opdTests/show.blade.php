@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.opdTest.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opd-tests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.opdTest.fields.id') }}
                        </th>
                        <td>
                            {{ $opdTest->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdTest.fields.opd') }}
                        </th>
                        <td>
                            {{ $opdTest->opd->visit_date ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdTest.fields.test') }}
                        </th>
                        <td>
                            @foreach($opdTest->tests as $key => $test)
                                <span class="label label-info">{{ $test->test_name }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdTest.fields.notes') }}
                        </th>
                        <td>
                            {!! $opdTest->notes !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opd-tests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection