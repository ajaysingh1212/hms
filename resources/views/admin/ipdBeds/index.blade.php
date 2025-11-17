@extends('layouts.admin')
@section('content')
@can('ipd_bed_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ipd-beds.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ipdBed.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'IpdBed', 'route' => 'admin.ipd-beds.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ipdBed.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-IpdBed">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ipdBed.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBed.fields.room') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.ward_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.charges_per_day') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBed.fields.bed_no') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBed.fields.charges_per_day') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBed.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipdBeds as $key => $ipdBed)
                        <tr data-entry-id="{{ $ipdBed->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ipdBed->id ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBed->room->room_no ?? '' }}
                            </td>
                            <td>
                                @if($ipdBed->room)
                                    {{ $ipdBed->room::WARD_TYPE_SELECT[$ipdBed->room->ward_type] ?? '' }}
                                @endif
                            </td>
                            <td>
                                {{ $ipdBed->room->charges_per_day ?? '' }}
                            </td>
                            <td>
                                @if($ipdBed->room)
                                    {{ $ipdBed->room::STATUS_SELECT[$ipdBed->room->status] ?? '' }}
                                @endif
                            </td>
                            <td>
                                {{ $ipdBed->bed_no ?? '' }}
                            </td>
                            <td>
                                {{ $ipdBed->charges_per_day ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\IpdBed::STATUS_SELECT[$ipdBed->status] ?? '' }}
                            </td>
                            <td>
                                @can('ipd_bed_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.ipd-beds.show', $ipdBed->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('ipd_bed_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.ipd-beds.edit', $ipdBed->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('ipd_bed_delete')
                                    <form action="{{ route('admin.ipd-beds.destroy', $ipdBed->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('ipd_bed_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ipd-beds.massDestroy') }}",
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
  let table = $('.datatable-IpdBed:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection