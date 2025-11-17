@extends('layouts.admin')
@section('content')
@can('lab_test_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.lab-tests.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.labTest.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'LabTest', 'route' => 'admin.lab-tests.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.labTest.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-LabTest">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.labTest.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.labTest.fields.test_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.labTest.fields.price') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($labTests as $key => $labTest)
                        <tr data-entry-id="{{ $labTest->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $labTest->id ?? '' }}
                            </td>
                            <td>
                                {{ $labTest->test_name ?? '' }}
                            </td>
                            <td>
                                {{ $labTest->price ?? '' }}
                            </td>
                            <td>
                                @can('lab_test_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.lab-tests.show', $labTest->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('lab_test_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.lab-tests.edit', $labTest->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('lab_test_delete')
                                    <form action="{{ route('admin.lab-tests.destroy', $labTest->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('lab_test_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.lab-tests.massDestroy') }}",
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
  let table = $('.datatable-LabTest:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection