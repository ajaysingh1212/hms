@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.ipdBilling.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-billings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.id') }}
                        </th>
                        <td>
                            {{ $ipdBilling->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.ipd') }}
                        </th>
                        <td>
                            {{ $ipdBilling->ipd->ipd_number ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.other_charges') }}
                        </th>
                        <td>
                            {{ $ipdBilling->other_charges }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.total') }}
                        </th>
                        <td>
                            {{ $ipdBilling->total }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.discount_type') }}
                        </th>
                        <td>
                            {{ App\Models\IpdBilling::DISCOUNT_TYPE_SELECT[$ipdBilling->discount_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.paid') }}
                        </th>
                        <td>
                            {{ $ipdBilling->paid }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.due') }}
                        </th>
                        <td>
                            {{ $ipdBilling->due }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.payment_type') }}
                        </th>
                        <td>
                            {{ App\Models\IpdBilling::PAYMENT_TYPE_SELECT[$ipdBilling->payment_type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.notes') }}
                        </th>
                        <td>
                            {!! $ipdBilling->notes !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.attechments') }}
                        </th>
                        <td>
                            @foreach($ipdBilling->attechments as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.ipd-billings.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection