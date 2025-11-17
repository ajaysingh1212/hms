@extends('layouts.admin')
@section('content')
@can('ipd_billing_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ipd-billings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ipdBilling.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'IpdBilling', 'route' => 'admin.ipd-billings.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ipdBilling.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-IpdBilling">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.ipd') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.condition_on_admission') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.other_charges') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.total') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.discount_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.paid') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.due') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.payment_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBilling.fields.attechments') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipdBillings as $key => $ipdBilling)
                        <tr data-entry-id="{{ $ipdBilling->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ipdBilling->id ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBilling->ipd->ipd_number ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBilling->ipd->admission_date ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBilling->ipd->admission_time ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBilling->ipd->condition_on_admission ?? '' }}
                            </td>
                            <td>
                                @if($ipdBilling->ipd)
                                    {{ $ipdBilling->ipd::STATUS_SELECT[$ipdBilling->ipd->status] ?? '' }}
                                @endif
                            </td>
                            <td>
                                {{ $ipdBilling->other_charges ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBilling->total ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\IpdBilling::DISCOUNT_TYPE_SELECT[$ipdBilling->discount_type] ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBilling->paid ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBilling->due ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\IpdBilling::PAYMENT_TYPE_SELECT[$ipdBilling->payment_type] ?? '' }}
                            </td>
                            <td>
                                @foreach($ipdBilling->attechments as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('ipd_billing_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.ipd-billings.show', $ipdBilling->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('ipd_billing_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.ipd-billings.edit', $ipdBilling->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('ipd_billing_delete')
                                    <form action="{{ route('admin.ipd-billings.destroy', $ipdBilling->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('ipd_billing_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ipd-billings.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-IpdBilling:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection