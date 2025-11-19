@extends('layouts.admin')
@section('content')

<style>
    .custom-card {
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .dropzone {
        border: 2px dashed #5a8dee !important;
        border-radius: 10px !important;
        background: #f7faff !important;
        padding: 25px !important;
    }
    .dropzone .dz-message {
        font-size: 20px !important;
        color: #5a8dee !important;
    }
</style>

<div class="card custom-card">
    <div class="card-header bg-primary text-white">
        <strong>{{ trans('global.edit') }} {{ trans('cruds.ipdMedication.title_singular') }}</strong>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.ipd-medications.update', [$ipdMedication->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="form-group col-lg-4">
                <label for="ipd_id">{{ trans('cruds.ipdMedication.fields.ipd') }}</label>
                <select class="form-control select2 {{ $errors->has('ipd') ? 'is-invalid' : '' }}" name="ipd_id" id="ipd_id">
                    @foreach($ipds as $id => $entry)
                        <option value="{{ $id }}" {{ (old('ipd_id') ? old('ipd_id') == $id : $ipdMedication->ipd_id == $id) ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ipd'))
                    <div class="invalid-feedback">{{ $errors->first('ipd') }}</div>
                @endif
            </div>

            <div id="ipd-info" style="display:none;" class="row mt-4">

                <div class="col-lg-3 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-info text-white">Patient Details</div>
                        <div class="card-body">
                            <p><b>Name:</b> <span id="p_name"></span></p>
                            <p><b>Mobile:</b> <span id="p_mobile"></span></p>
                            <p><b>Reason:</b> <span id="p_reason"></span></p>
                            <p><b>Department:</b> <span id="p_department"></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-success text-white">Doctor Details</div>
                        <div class="card-body">
                            <p><b>Name:</b> <span id="d_name"></span></p>
                            <p><b>Department:</b> <span id="d_department"></span></p>
                            <p><b>Fee:</b> ₹<span id="d_fee"></span></p>
                            <p><b>Experience:</b> <span id="d_exp"></span> Yrs</p>
                            <p><b>Qualifications:</b> <span id="d_qual"></span></p>
                            <p><b>Phone:</b> <span id="d_phone"></span></p>
                            <p><b>Available Days:</b> <span id="d_days"></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-warning">IPD Details</div>
                        <div class="card-body">
                            <p><b>IPD No:</b> <span id="i_no"></span></p>
                            <p><b>Date:</b> <span id="i_date"></span></p>
                            <p><b>Time:</b> <span id="i_time"></span></p>
                            <p><b>Room:</b> <span id="i_room"></span></p>
                            <p><b>Bed:</b> <span id="i_bed"></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-danger text-white">IPD Treatment</div>
                        <div class="card-body">
                            <p><b>Date:</b> <span id="t_date"></span></p>
                            <p><b>Notes:</b> <span id="t_notes"></span></p>
                            <p><b>Diagnosis:</b> <span id="t_diag"></span></p>
                            <p><b>Treatment:</b> <span id="t_treat"></span></p>

                            <b>Attachments:</b>
                            <ul id="t_files"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">

                <div class="form-group col-lg-4">
                    <label for="medicines">{{ trans('cruds.ipdMedication.fields.medicine') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('medicines') ? 'is-invalid' : '' }}" name="medicines[]" id="medicines" multiple>
                        @foreach($medicines as $id => $medicine)
                            <option value="{{ $id }}" {{ (in_array($id, old('medicines', [])) || $ipdMedication->medicines->contains($id)) ? 'selected' : '' }}>{{ $medicine }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('medicines'))
                        <div class="invalid-feedback">{{ $errors->first('medicines') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-4">
                    <label for="dosage">{{ trans('cruds.ipdMedication.fields.dosage') }}</label>
                    <input class="form-control {{ $errors->has('dosage') ? 'is-invalid' : '' }}" type="text" name="dosage" id="dosage" value="{{ old('dosage', $ipdMedication->dosage) }}">
                    @if($errors->has('dosage'))
                        <div class="invalid-feedback">{{ $errors->first('dosage') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-4">
                    <label for="frequency">{{ trans('cruds.ipdMedication.fields.frequency') }}</label>
                    <input class="form-control {{ $errors->has('frequency') ? 'is-invalid' : '' }}" type="text" name="frequency" id="frequency" value="{{ old('frequency', $ipdMedication->frequency) }}">
                    @if($errors->has('frequency'))
                        <div class="invalid-feedback">{{ $errors->first('frequency') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-4">
                    <label>{{ trans('cruds.ipdMedication.fields.route') }}</label>
                    <select class="form-control {{ $errors->has('route') ? 'is-invalid' : '' }}" name="route" id="route">
                        <option value disabled {{ old('route', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                        @foreach(App\Models\IpdMedication::ROUTE_SELECT as $key => $label)
                            <option value="{{ $key }}" {{ old('route', $ipdMedication->route) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('route'))
                        <div class="invalid-feedback">{{ $errors->first('route') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-12">
                    <label for="notes">{{ trans('cruds.ipdMedication.fields.notes') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{!! old('notes', $ipdMedication->notes) !!}</textarea>
                    @if($errors->has('notes'))
                        <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
                    @endif
                </div>

                <div class="form-group col-lg-12">
                    <label for="attechment">{{ trans('cruds.ipdMedication.fields.attechment') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('attechment') ? 'is-invalid' : '' }}" id="attechment-dropzone"></div>
                    @if($errors->has('attechment'))
                        <div class="invalid-feedback">{{ $errors->first('attechment') }}</div>
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
$('#ipd_id').change(function () {

    let id = $(this).val();
    if (!id) return;

    $.ajax({
        url: "{{ route('admin.ipd-medications.getIpdDetails') }}",
        data: { ipd_id: id },
        success: function(res) {

            $('#ipd-info').show();

            $('#p_name').text(res.patient.patient_name);
            $('#p_mobile').text(res.patient.mobile_number);
            $('#p_reason').text(res.patient.reason_for_visit);
            $('#p_department').text(res.patient.department_name);

            $('#d_name').text(res.doctor.doctor_name);
            $('#d_department').text(res.doctor.doctor_department);
            $('#d_fee').text(res.doctor.doctor_fee);
            $('#d_exp').text(res.doctor.experience);
            $('#d_qual').text(res.doctor.qualifications);
            $('#d_phone').text(res.doctor.phone);

            let days = "";
            (res.doctor.available_days || []).forEach(x => {
                days += `<span class="badge badge-primary mr-1">${x}</span>`;
            });
            $('#d_days').html(days);

            $('#i_no').text(res.ipd.ipd_number);
            $('#i_date').text(res.ipd.admission_date);
            $('#i_time').text(res.ipd.admission_time);

            $('#i_room').text(res.room ? res.room.room_no : '-');
            $('#i_bed').text(res.bed ? res.bed.bed_no : '-');

            $('#t_date').text(res.treatment.date ?? '-');
            $('#t_notes').html(res.treatment.doctor_notes ?? '-');
            $('#t_diag').html(res.treatment.diagnosis ?? '-');
            $('#t_treat').html(res.treatment.treatment ?? '-');

            let files = "";
            (res.treatment.attachments || []).forEach(f => {
                files += `<li><a target="_blank" href="/storage/${f}">${f}</a></li>`;
            });
            $('#t_files').html(files);

        }
    });
});
</script>

<script>
$(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file.then(function (file) {
            return new Promise(function(resolve, reject) {

              var xhr = new XMLHttpRequest();
              xhr.open('POST', '{{ route('admin.ipd-medications.storeCKEditorImages') }}', true);
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
              data.append('crud_id', '{{ $ipdMedication->id ?? 0 }}');
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
var uploadedAttechmentMap = {}
Dropzone.options.attechmentDropzone = {
    url: '{{ route('admin.ipd-medications.storeMedia') }}',
    maxFilesize: 20,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 20 },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attechment[]" value="' + response.name + '">')
      uploadedAttechmentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = file.file_name !== undefined ? file.file_name : uploadedAttechmentMap[file.name]
      $('form').find('input[name="attechment[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ipdMedication) && $ipdMedication->attechment)
      var files = {!! json_encode($ipdMedication->attechment) !!}
      for (var i in files) {
        var file = files[i]
        this.options.addedfile.call(this, file)
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="attechment[]" value="' + file.file_name + '">')
      }
@endif
    },
     error: function (file, response) {
         var message = $.type(response) === 'string' ? response : response.errors.file
         file.previewElement.classList.add('dz-error')
         var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         for (var i = 0; i < _ref.length; i++) {
            _ref[i].textContent = message
         }
     }
}
</script>

@endsection
