@can('opd_test_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.opd-tests.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.opdTest.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.opdTest.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-testOpdTests">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.opdTest.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdTest.fields.opd') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdVisit.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdTest.fields.test') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opdTests as $key => $opdTest)
                        <tr data-entry-id="{{ $opdTest->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $opdTest->id ?? '' }}
                            </td>
                            <td>
                                {{ $opdTest->opd->visit_date ?? '' }}
                            </td>
                            <td>
                                @if($opdTest->opd)
                                    {{ $opdTest->opd::STATUS_SELECT[$opdTest->opd->status] ?? '' }}
                                @endif
                            </td>
                            <td>
                                @foreach($opdTest->tests as $key => $item)
                                    <span class="badge badge-info">{{ $item->test_name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @can('opd_test_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.opd-tests.show', $opdTest->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('opd_test_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.opd-tests.edit', $opdTest->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('opd_test_delete')
                                    <form action="{{ route('admin.opd-tests.destroy', $opdTest->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('opd_test_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.opd-tests.massDestroy') }}",
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
  let table = $('.datatable-testOpdTests:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection