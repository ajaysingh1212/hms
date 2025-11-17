@can('ipd_admission_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.ipd-admissions.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.ipdAdmission.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.ipdAdmission.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-patientIpdAdmissions">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.patient') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.mobile_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.appointment.fields.date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.doctor') }}
                        </th>
                        <th>
                            {{ trans('cruds.addDoctor.fields.available_days') }}
                        </th>
                        <th>
                            {{ trans('cruds.addDoctor.fields.doctor_fee') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.admission_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.room') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdRoom.fields.ward_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.bed') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdBed.fields.charges_per_day') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.condition_on_admission') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.attechment') }}
                        </th>
                        <th>
                            {{ trans('cruds.ipdAdmission.fields.ipd_number') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ipdAdmissions as $key => $ipdAdmission)
                        <tr data-entry-id="{{ $ipdAdmission->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $ipdAdmission->id ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->patient->patient_number ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->patient->mobile_number ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->patient->date ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->doctor->doctor_name ?? '' }}
                            </td>
                            <td>
                                @if($ipdAdmission->doctor)
                                    {{ $ipdAdmission->doctor::AVAILABLE_DAYS_RADIO[$ipdAdmission->doctor->available_days] ?? '' }}
                                @endif
                            </td>
                            <td>
                                {{ $ipdAdmission->doctor->doctor_fee ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->admission_date ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->admission_time ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->room->room_no ?? '' }}
                            </td>
                            <td>
                                @if($ipdAdmission->room)
                                    {{ $ipdAdmission->room::WARD_TYPE_SELECT[$ipdAdmission->room->ward_type] ?? '' }}
                                @endif
                            </td>
                            <td>
                                {{ $ipdAdmission->bed->bed_no ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->bed->charges_per_day ?? '' }}
                            </td>
                            <td>
                                {{ $ipdAdmission->condition_on_admission ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\IpdAdmission::STATUS_SELECT[$ipdAdmission->status] ?? '' }}
                            </td>
                            <td>
                                @if($ipdAdmission->attechment)
                                    <a href="{{ $ipdAdmission->attechment->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $ipdAdmission->ipd_number ?? '' }}
                            </td>
                            <td>
                                @can('ipd_admission_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.ipd-admissions.show', $ipdAdmission->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('ipd_admission_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.ipd-admissions.edit', $ipdAdmission->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('ipd_admission_delete')
                                    <form action="{{ route('admin.ipd-admissions.destroy', $ipdAdmission->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('ipd_admission_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.ipd-admissions.massDestroy') }}",
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
  let table = $('.datatable-patientIpdAdmissions:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection