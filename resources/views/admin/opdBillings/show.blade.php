@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.opdBilling.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opd-billings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.id') }}
                        </th>
                        <td>
                            {{ $opdBilling->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.opd') }}
                        </th>
                        <td>
                            {{ $opdBilling->opd->visit_date ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.total') }}
                        </th>
                        <td>
                            {{ $opdBilling->total }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.discount_type') }}
                        </th>
                        <td>
                            {{ App\Models\OpdBilling::DISCOUNT_TYPE_SELECT[$opdBilling->discount_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.paid') }}
                        </th>
                        <td>
                            {{ $opdBilling->paid }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.due') }}
                        </th>
                        <td>
                            {{ $opdBilling->due }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.payment_type') }}
                        </th>
                        <td>
                            {{ App\Models\OpdBilling::PAYMENT_TYPE_SELECT[$opdBilling->payment_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.attechment') }}
                        </th>
                        <td>
                            @foreach($opdBilling->attechment as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.opdBilling.fields.notes') }}
                        </th>
                        <td>
                            {!! $opdBilling->notes !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.opd-billings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection