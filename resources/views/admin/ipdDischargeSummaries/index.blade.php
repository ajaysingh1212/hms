@extends('layouts.admin')
@section('content')
@can('ipd_discharge_summary_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ipd-discharge-summaries.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ipdDischargeSummary.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'IpdDischargeSummary', 'route' => 'admin.ipd-discharge-summaries.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ipdDischargeSummary.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-IpdDischargeSummary">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.ipd') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.discharge_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.condition_on_discharge') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.followup_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdDischargeSummary.fields.attechments') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipdDischargeSummaries as $key => $ipdDischargeSummary)
                        <tr data-entry-id="{{ $ipdDischargeSummary->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ipdDischargeSummary->id ?? '' }}
                            </td>
                            <td>
                                {{ $ipdDischargeSummary->ipd->ipd_number ?? '' }}
                            </td>
                            <td>
                                {{ $ipdDischargeSummary->ipd->admission_date ?? '' }}
                            </td>
                            <td>
                                {{ $ipdDischargeSummary->discharge_date ?? '' }}
                            </td>
                            <td>
                                {{ $ipdDischargeSummary->condition_on_discharge ?? '' }}
                            </td>
                            <td>
                                {{ $ipdDischargeSummary->followup_date ?? '' }}
                            </td>
                            <td>
                                @if($ipdDischargeSummary->attechments)
                                    <a href="{{ $ipdDischargeSummary->attechments->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @can('ipd_discharge_summary_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.ipd-discharge-summaries.show', $ipdDischargeSummary->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('ipd_discharge_summary_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.ipd-discharge-summaries.edit', $ipdDischargeSummary->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('ipd_discharge_summary_delete')
                                    <form action="{{ route('admin.ipd-discharge-summaries.destroy', $ipdDischargeSummary->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('ipd_discharge_summary_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ipd-discharge-summaries.massDestroy') }}",
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
  let table = $('.datatable-IpdDischargeSummary:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection