@extends('layouts.admin')
@section('content')

<style>
    .custom-card { border-radius: 10px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); }
    .info-badge { font-size: 13px; padding: 6px 10px; border-radius: 20px; background:#eef7ff; color:#1e6fd8; }
    .dropzone { border: 2px dashed #1e6fd8 !important; border-radius: 10px !important; background: #fbfdff !important; padding: 22px !important; }
    .dropzone .dz-message { font-size: 18px !important; color: #1e6fd8 !important; }
    .card-header-compact { padding: 10px 14px; font-weight:600; }
    .small-muted { font-size:13px; color:#6c757d; }
</style>

<div class="card custom-card">
    <div class="card-header bg-primary text-white card-header-compact">
        <strong>{{ trans('global.edit') }} {{ trans('cruds.ipdVital.title_singular') }}</strong>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.ipd-vitals.update', [$ipdVital->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="ipd_id">{{ trans('cruds.ipdVital.fields.ipd') }}</label>
                        <select class="form-control select2 {{ $errors->has('ipd') ? 'is-invalid' : '' }}" name="ipd_id" id="ipd_id">
                            @foreach($ipds as $id => $entry)
                                <option value="{{ $id }}" {{ (old('ipd_id') ? old('ipd_id') == $id : $ipdVital->ipd_id == $id) ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('ipd'))
                            <div class="invalid-feedback">{{ $errors->first('ipd') }}</div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-8 d-flex align-items-center">
                    <div class="w-100">
                        <div class="small-muted">Select IPD to auto-load Patient, Doctor & Medication details below</div>
                    </div>
                </div>
            </div>

            <div id="ipd-cards" style="display:none;" class="row mt-3">
                <div class="col-lg-4 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-info text-white card-header-compact">Patient Details</div>
                        <div class="card-body">
                            <p><span class="info-badge">Name</span> <strong id="p_name" class="ml-2"></strong></p>
                            <p><span class="info-badge">Mobile</span> <strong id="p_mobile" class="ml-2"></strong></p>
                            <p><span class="info-badge">Department</span> <strong id="p_department" class="ml-2"></strong></p>
                            <p class="small-muted"><b>Reason:</b> <span id="p_reason"></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-success text-white card-header-compact">Doctor Details</div>
                        <div class="card-body">
                            <p><span class="info-badge">Name</span> <strong id="d_name" class="ml-2"></strong></p>
                            <p><span class="info-badge">Department</span> <strong id="d_department" class="ml-2"></strong></p>
                            <p><span class="info-badge">Fee</span> <strong>₹<span id="d_fee"></span></strong></p>
                            <p class="small-muted"><b>Experience:</b> <span id="d_exp"></span> Yrs</p>
                            <p class="small-muted"><b>Qualifications:</b> <span id="d_qual"></span></p>
                            <p class="small-muted"><b>Phone:</b> <span id="d_phone"></span></p>
                            <div class="mt-2" id="d_days"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-warning card-header-compact">Latest Medication</div>
                        <div class="card-body">
                            <p class="small-muted"><b>Medicines</b></p>
                            <ul id="m_medicines"></ul>
                            <p class="small-muted"><b>Dosage:</b> <span id="m_dosage"></span></p>
                            <p class="small-muted"><b>Frequency:</b> <span id="m_frequency"></span></p>
                            <p class="small-muted"><b>Route:</b> <span id="m_route"></span></p>
                            <p class="small-muted"><b>Notes:</b> <span id="m_notes"></span></p>
                            <div>
                                <b>Attachments:</b>
                                <ul id="m_attachments"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">

                <div class="form-group col-lg-4">
                    <label for="date_time">{{ trans('cruds.ipdVital.fields.date_time') }}</label>
                    <input class="form-control datetime {{ $errors->has('date_time') ? 'is-invalid' : '' }}" type="text" name="date_time" id="date_time" value="{{ old('date_time', $ipdVital->date_time) }}">
                    @if($errors->has('date_time'))
                        <div class="invalid-feedback">{{ $errors->first('date_time') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-4">
                    <label for="temperature">{{ trans('cruds.ipdVital.fields.temperature') }}</label>
                    <input class="form-control {{ $errors->has('temperature') ? 'is-invalid' : '' }}" type="text" name="temperature" id="temperature" value="{{ old('temperature', $ipdVital->temperature) }}">
                    @if($errors->has('temperature'))
                        <div class="invalid-feedback">{{ $errors->first('temperature') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-4">
                    <label for="pulse">{{ trans('cruds.ipdVital.fields.pulse') }}</label>
                    <input class="form-control {{ $errors->has('pulse') ? 'is-invalid' : '' }}" type="text" name="pulse" id="pulse" value="{{ old('pulse', $ipdVital->pulse) }}">
                    @if($errors->has('pulse'))
                        <div class="invalid-feedback">{{ $errors->first('pulse') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-4">
                    <label for="bp">{{ trans('cruds.ipdVital.fields.bp') }}</label>
                    <input class="form-control {{ $errors->has('bp') ? 'is-invalid' : '' }}" type="text" name="bp" id="bp" value="{{ old('bp', $ipdVital->bp) }}">
                    @if($errors->has('bp'))
                        <div class="invalid-feedback">{{ $errors->first('bp') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-4">
                    <label for="spo_2">{{ trans('cruds.ipdVital.fields.spo_2') }}</label>
                    <input class="form-control {{ $errors->has('spo_2') ? 'is-invalid' : '' }}" type="text" name="spo_2" id="spo_2" value="{{ old('spo_2', $ipdVital->spo_2) }}">
                    @if($errors->has('spo_2'))
                        <div class="invalid-feedback">{{ $errors->first('spo_2') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-4">
                    <label for="respiration">{{ trans('cruds.ipdVital.fields.respiration') }}</label>
                    <input class="form-control {{ $errors->has('respiration') ? 'is-invalid' : '' }}" type="text" name="respiration" id="respiration" value="{{ old('respiration', $ipdVital->respiration) }}">
                    @if($errors->has('respiration'))
                        <div class="invalid-feedback">{{ $errors->first('respiration') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-12">
                    <label for="notes">{{ trans('cruds.ipdVital.fields.notes') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{!! old('notes', $ipdVital->notes) !!}</textarea>
                    @if($errors->has('notes'))
                        <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-12">
                    <label for="attechments">{{ trans('cruds.ipdVital.fields.attechments') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('attechments') ? 'is-invalid' : '' }}" id="attechments-dropzone"></div>
                    @if($errors->has('attechments'))
                        <div class="invalid-feedback">{{ $errors->first('attechments') }}</div>
                    @endif
                </div>

            </div>

            <div class="form-group">
                <button class="btn btn-danger btn-lg px-4" type="submit">{{ trans('global.update') }}</button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function loadIpdDetails(id) {
    if (!id) return;
    $.ajax({
        url: "{{ route('admin.ipd-vitals.getIpdFullDetails') }}",
        data: { ipd_id: id },
        success: function(res) {
            $('#ipd-cards').show();
            $('#p_name').text(res.patient ? (res.patient.patient_name || '-') : '-');
            $('#p_mobile').text(res.patient ? (res.patient.mobile_number || '-') : '-');
            $('#p_reason').text(res.patient ? (res.patient.reason_for_visit || '-') : '-');
            $('#p_department').text(res.patient ? (res.patient.department_name || '-') : '-');
            $('#d_name').text(res.doctor ? (res.doctor.doctor_name || '-') : '-');
            $('#d_department').text(res.doctor ? (res.doctor.doctor_department || '-') : '-');
            $('#d_fee').text(res.doctor ? (res.doctor.doctor_fee || '-') : '-');
            $('#d_exp').text(res.doctor ? (res.doctor.experience || '-') : '-');
            $('#d_qual').text(res.doctor ? (res.doctor.qualifications || '-') : '-');
            $('#d_phone').text(res.doctor ? (res.doctor.phone || '-') : '-');
            let days = "";
            (res.doctor && res.doctor.available_days || []).forEach(x => {
                days += `<span class="badge badge-pill badge-primary mr-1">${x}</span>`;
            });
            $('#d_days').html(days);
            $('#m_medicines').html('');
            (res.medication.medicines || []).forEach(m => {
                $('#m_medicines').append(`<li>${m.name}</li>`);
            });
            $('#m_dosage').text(res.medication.dosage || '-');
            $('#m_frequency').text(res.medication.frequency || '-');
            $('#m_route').text(res.medication.route_label || '-');
            $('#m_notes').text(res.medication.notes || '-');
            let files = "";
            (res.medication.attachments || []).forEach(f => {
                files += `<li><a target="_blank" href="/storage/${f}">${f}</a></li>`;
            });
            $('#m_attachments').html(files);
        }
    });
}

$('#ipd_id').change(function () {
    let id = $(this).val();
    loadIpdDetails(id);
});

$(document).ready(function () {
  let initialIpd = '{{ old('ipd_id', $ipdVital->ipd_id) }}';
  if (initialIpd) {
    $('#ipd-cards').show();
    loadIpdDetails(initialIpd);
  }
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file.then(function (file) {
            return new Promise(function(resolve, reject) {
              var xhr = new XMLHttpRequest();
              xhr.open('POST', '{{ route('admin.ipd-vitals.storeCKEditorImages') }}', true);
              xhr.setRequestHeader('x-csrf-token', window._token);
              xhr.setRequestHeader('Accept', 'application/json');
              xhr.responseType = 'json';
              var genericErrorText = `Couldn't upload file: ${ file.name }.`;
              xhr.addEventListener('error', function() { reject(genericErrorText) });
              xhr.addEventListener('abort', function() { reject() });
              xhr.addEventListener('load', function() {
                var response = xhr.response;
                if (!response || xhr.status !== 201) {
                  return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                }
                $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');
                resolve({ default: response.url });
              });
              if (xhr.upload) {
                xhr.upload.addEventListener('progress', function(e) {
                  if (e.lengthComputable) {
                    loader.uploadTotal = e.total;
                    loader.uploaded = e.loaded;
                  }
                });
              }
              var data = new FormData();
              data.append('upload', file);
              data.append('crud_id', '{{ $ipdVital->id ?? 0 }}');
              xhr.send(data);
            });
          })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(allEditors[i], { extraPlugins: [SimpleUploadAdapter] });
  }
});
</script>

<script>
Dropzone.options.attechmentsDropzone = {
    url: '{{ route('admin.ipd-vitals.storeMedia') }}',
    maxFilesize: 20,
    maxFiles: 1,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 20 },
    success: function (file, response) {
      $('form').find('input[name="attechments"]').remove();
      $('form').append('<input type="hidden" name="attechments" value="' + response.name + '">');
    },
    removedfile: function (file) {
      file.previewElement.remove();
      if (file.status !== 'error') {
        $('form').find('input[name="attechments"]').remove();
        this.options.maxFiles = this.options.maxFiles + 1;
      }
    },
    init: function () {
@if(isset($ipdVital) && $ipdVital->attechments)
      var file = {!! json_encode($ipdVital->attechments) !!};
      this.options.addedfile.call(this, file);
      file.previewElement.classList.add('dz-complete');
      $('form').append('<input type="hidden" name="attechments" value="' + file.file_name + '">');
      this.options.maxFiles = this.options.maxFiles - 1;
@endif
    },
     error: function (file, response) {
         var message = $.type(response) === 'string' ? response : response.errors.file;
         file.previewElement.classList.add('dz-error');
         var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]');
         for (var i = 0; i < _ref.length; i++) {
            _ref[i].textContent = message;
         }
     }
}
</script>

@endsection
