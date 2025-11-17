@extends('layouts.admin')
@section('content')
@can('opd_prescription_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.opd-prescriptions.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.opdPrescription.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'OpdPrescription', 'route' => 'admin.opd-prescriptions.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.opdPrescription.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-OpdPrescription">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.opd') }}
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
                            {{ trans('cruds.opdPrescription.fields.medicine') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.dosage') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.duration') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdPrescription.fields.attechment') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opdPrescriptions as $key => $opdPrescription)
                        <tr data-entry-id="{{ $opdPrescription->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $opdPrescription->id ?? '' }}
                            </td>
                            <td>
                                {{ $opdPrescription->opd->visit_date ?? '' }}
                            </td>
                            <td>
                                {{ $opdPrescription->opd->visit_time ?? '' }}
                            </td>
                            <td>
                                @if($opdPrescription->opd)
                                    {{ $opdPrescription->opd::VISIT_TYPE_SELECT[$opdPrescription->opd->visit_type] ?? '' }}
                                @endif
                            </td>
                            <td>
                                @if($opdPrescription->opd)
                                    {{ $opdPrescription->opd::STATUS_SELECT[$opdPrescription->opd->status] ?? '' }}
                                @endif
                            </td>
                            <td>
                                @foreach($opdPrescription->medicines as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $opdPrescription->dosage ?? '' }}
                            </td>
                            <td>
                                {{ $opdPrescription->duration ?? '' }}
                            </td>
                            <td>
                                @foreach($opdPrescription->attechment as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('opd_prescription_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.opd-prescriptions.show', $opdPrescription->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('opd_prescription_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.opd-prescriptions.edit', $opdPrescription->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('opd_prescription_delete')
                                    <form action="{{ route('admin.opd-prescriptions.destroy', $opdPrescription->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('opd_prescription_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.opd-prescriptions.massDestroy') }}",
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
  let table = $('.datatable-OpdPrescription:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection