@can('ipd_medication_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ipd-medications.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ipdMedication.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.ipdMedication.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ipdIpdMedications">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.ipd') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.medicine') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.dosage') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.frequency') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.route') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdMedication.fields.attechment') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipdMedications as $key => $ipdMedication)
                        <tr data-entry-id="{{ $ipdMedication->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ipdMedication->id ?? '' }}
                            </td>
                            <td>
                                {{ $ipdMedication->ipd->ipd_number ?? '' }}
                            </td>
                            <td>
                                {{ $ipdMedication->ipd->admission_date ?? '' }}
                            </td>
                            <td>
                                @foreach($ipdMedication->medicines as $key => $item)
                                    <span class="badge badge-info">{{ $item->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $ipdMedication->dosage ?? '' }}
                            </td>
                            <td>
                                {{ $ipdMedication->frequency ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\IpdMedication::ROUTE_SELECT[$ipdMedication->route] ?? '' }}
                            </td>
                            <td>
                                @foreach($ipdMedication->attechment as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('ipd_medication_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.ipd-medications.show', $ipdMedication->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('ipd_medication_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.ipd-medications.edit', $ipdMedication->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('ipd_medication_delete')
                                    <form action="{{ route('admin.ipd-medications.destroy', $ipdMedication->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('ipd_medication_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ipd-medications.massDestroy') }}",
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
  let table = $('.datatable-ipdIpdMedications:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection