@extends('layouts.admin')
@section('content')
@can('opd_visit_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.opd-visits.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.opdVisit.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'OpdVisit', 'route' => 'admin.opd-visits.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.opdVisit.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-OpdVisit">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.opdVisit.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdVisit.fields.patient') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.patient_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.mobile_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdVisit.fields.doctor') }}
                        </th>
                        <th>
                            {{ trans('cruds.addDoctor.fields.available_days') }}
                        </th>
                        <th>
                            {{ trans('cruds.addDoctor.fields.doctor_fee') }}
                        </th>
                        <th>
                            {{ trans('cruds.opdVisit.fields.visit_date') }}
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
                            {{ trans('cruds.opdVisit.fields.attechment') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($opdVisits as $key => $opdVisit)
                        <tr data-entry-id="{{ $opdVisit->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $opdVisit->id ?? '' }}
                            </td>
                            <td>
                                {{ $opdVisit->patient->patient_number ?? '' }}
                            </td>
                            <td>
                                {{ $opdVisit->patient->patient_name ?? '' }}
                            </td>
                            <td>
                                {{ $opdVisit->patient->mobile_number ?? '' }}
                            </td>
                            <td>
                                {{ $opdVisit->doctor->doctor_name ?? '' }}
                            </td>
                            <td>
                                @if($opdVisit->doctor)
                                    {{ $opdVisit->doctor::AVAILABLE_DAYS_RADIO[$opdVisit->doctor->available_days] ?? '' }}
                                @endif
                            </td>
                            <td>
                                {{ $opdVisit->doctor->doctor_fee ?? '' }}
                            </td>
                            <td>
                                {{ $opdVisit->visit_date ?? '' }}
                            </td>
                            <td>
                                {{ $opdVisit->visit_time ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\OpdVisit::VISIT_TYPE_SELECT[$opdVisit->visit_type] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\OpdVisit::STATUS_SELECT[$opdVisit->status] ?? '' }}
                            </td>
                            <td>
                                @foreach($opdVisit->attechment as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('opd_visit_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.opd-visits.show', $opdVisit->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('opd_visit_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.opd-visits.edit', $opdVisit->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('opd_visit_delete')
                                    <form action="{{ route('admin.opd-visits.destroy', $opdVisit->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('opd_visit_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.opd-visits.massDestroy') }}",
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
  let table = $('.datatable-OpdVisit:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection