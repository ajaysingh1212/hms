@extends('layouts.admin')
@section('content')
@can('opd_billing_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.opd-billings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.opdBilling.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'OpdBilling', 'route' => 'admin.opd-billings.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.opdBilling.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-OpdBilling">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.opdBilling.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdBilling.fields.opd') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdVisit.fields.visit_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdVisit.fields.visit_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdVisit.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdBilling.fields.total') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdBilling.fields.discount_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdBilling.fields.paid') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdBilling.fields.due') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdBilling.fields.payment_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdBilling.fields.attechment') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opdBillings as $key => $opdBilling)
                        <tr data-entry-id="{{ $opdBilling->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $opdBilling->id ?? '' }}
                            </td>
                            <td>
                                {{ $opdBilling->opd->visit_date ?? '' }}
                            </td>
                            <td>
                                {{ $opdBilling->opd->visit_time ?? '' }}
                            </td>
                            <td>
                                @if($opdBilling->opd)
                                    {{ $opdBilling->opd::VISIT_TYPE_SELECT[$opdBilling->opd->visit_type] ?? '' }}
                                @endif
                            </td>
                            <td>
                                @if($opdBilling->opd)
                                    {{ $opdBilling->opd::STATUS_SELECT[$opdBilling->opd->status] ?? '' }}
                                @endif
                            </td>
                            <td>
                                {{ $opdBilling->total ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\OpdBilling::DISCOUNT_TYPE_SELECT[$opdBilling->discount_type] ?? '' }}
                            </td>
                            <td>
                                {{ $opdBilling->paid ?? '' }}
                            </td>
                            <td>
                                {{ $opdBilling->due ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\OpdBilling::PAYMENT_TYPE_SELECT[$opdBilling->payment_type] ?? '' }}
                            </td>
                            <td>
                                @foreach($opdBilling->attechment as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('opd_billing_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.opd-billings.show', $opdBilling->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('opd_billing_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.opd-billings.edit', $opdBilling->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('opd_billing_delete')
                                    <form action="{{ route('admin.opd-billings.destroy', $opdBilling->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('opd_billing_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.opd-billings.massDestroy') }}",
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
  let table = $('.datatable-OpdBilling:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection