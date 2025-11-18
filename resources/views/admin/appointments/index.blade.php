@extends('layouts.admin')
@section('content')
@can('appointment_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.appointments.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.appointment.title_singular') }}
            </a>
            <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Appointment', 'route' => 'admin.appointments.parseCsvImport'])
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.appointment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Appointment">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.patient_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.department') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.doctor') }}
                        </th>
                        <th>
                            {{ trans('cruds.addDoctor.fields.available_days') }}
                        </th>
                        <th>
                            {{ trans('cruds.addDoctor.fields.appointment_slot_duration') }}
                        </th>
                        <th>
                            {{ trans('cruds.addDoctor.fields.doctor_fee') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.available_slots') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.patient_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.mobile_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.date') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.appointment_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $key => $appointment)
                        <tr data-entry-id="{{ $appointment->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $appointment->id ?? '' }}
                            </td>
                            <td>
                                {{ $appointment->patient_number ?? '' }}
                            </td>
                            <td>
                                {{ $appointment->department->name ?? '' }}
                            </td>
                            <td>
                                {{ $appointment->doctor->doctor_name ?? '' }}
                            </td>
                            <td>
                                @if(is_array($appointment->available_days))
                                    {{ implode(', ', array_map('ucfirst', $appointment->available_days)) }}
                                @endif
                            </td>

                            <td>
                                {{ $appointment->doctor->appointment_slot_duration ?? '' }}
                            </td>
                            <td>
                                {{ $appointment->doctor->doctor_fee ?? '' }}
                            </td>
                            <td>
                                @php
                                    $slots = is_array($appointment->doctor_slots)
                                        ? $appointment->doctor_slots
                                        : json_decode($appointment->doctor_slots, true);
                                @endphp

                                @if(is_array($slots))
                                    {{ implode(', ', $slots) }}
                                @endif
                            </td>

                            <td>
                                {{ $appointment->patient_name ?? '' }}
                            </td>
                            <td>
                                {{ $appointment->mobile_number ?? '' }}
                            </td>
                            <td>
                                {{ $appointment->date ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Appointment::APPOINTMENT_TYPE_SELECT[$appointment->appointment_type] ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Appointment::STATUS_SELECT[$appointment->status] ?? '' }}
                            </td>
                            <td>
                                @can('appointment_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.appointments.show', $appointment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('appointment_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.appointments.edit', $appointment->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('appointment_delete')
                                    <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('appointment_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.appointments.massDestroy') }}",
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
  let table = $('.datatable-Appointment:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection