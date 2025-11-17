@extends('layouts.admin')
@section('content')
@can('ipd_treatment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ipd-treatments.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ipdTreatment.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'IpdTreatment', 'route' => 'admin.ipd-treatments.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ipdTreatment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-IpdTreatment">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.ipd') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdTreatment.fields.attechment') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipdTreatments as $key => $ipdTreatment)
                        <tr data-entry-id="{{ $ipdTreatment->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ipdTreatment->id ?? '' }}
                            </td>
                            <td>
                                {{ $ipdTreatment->ipd->ipd_number ?? '' }}
                            </td>
                            <td>
                                {{ $ipdTreatment->ipd->admission_date ?? '' }}
                            </td>
                            <td>
                                {{ $ipdTreatment->date ?? '' }}
                            </td>
                            <td>
                                @if($ipdTreatment->attechment)
                                    <a href="{{ $ipdTreatment->attechment->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @can('ipd_treatment_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.ipd-treatments.show', $ipdTreatment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('ipd_treatment_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.ipd-treatments.edit', $ipdTreatment->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('ipd_treatment_delete')
                                    <form action="{{ route('admin.ipd-treatments.destroy', $ipdTreatment->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('ipd_treatment_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ipd-treatments.massDestroy') }}",
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
  let table = $('.datatable-IpdTreatment:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection