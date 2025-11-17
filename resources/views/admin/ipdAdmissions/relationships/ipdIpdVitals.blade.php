@can('ipd_vital_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ipd-vitals.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ipdVital.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.ipdVital.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ipdIpdVitals">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.ipd') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.date_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.temperature') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.pulse') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.bp') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.spo_2') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.respiration') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdVital.fields.attechments') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipdVitals as $key => $ipdVital)
                        <tr data-entry-id="{{ $ipdVital->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ipdVital->id ?? '' }}
                            </td>
                            <td>
                                {{ $ipdVital->ipd->ipd_number ?? '' }}
                            </td>
                            <td>
                                {{ $ipdVital->ipd->admission_date ?? '' }}
                            </td>
                            <td>
                                {{ $ipdVital->date_time ?? '' }}
                            </td>
                            <td>
                                {{ $ipdVital->temperature ?? '' }}
                            </td>
                            <td>
                                {{ $ipdVital->pulse ?? '' }}
                            </td>
                            <td>
                                {{ $ipdVital->bp ?? '' }}
                            </td>
                            <td>
                                {{ $ipdVital->spo_2 ?? '' }}
                            </td>
                            <td>
                                {{ $ipdVital->respiration ?? '' }}
                            </td>
                            <td>
                                @if($ipdVital->attechments)
                                    <a href="{{ $ipdVital->attechments->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @can('ipd_vital_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.ipd-vitals.show', $ipdVital->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('ipd_vital_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.ipd-vitals.edit', $ipdVital->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('ipd_vital_delete')
                                    <form action="{{ route('admin.ipd-vitals.destroy', $ipdVital->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('ipd_vital_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ipd-vitals.massDestroy') }}",
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
  let table = $('.datatable-ipdIpdVitals:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection