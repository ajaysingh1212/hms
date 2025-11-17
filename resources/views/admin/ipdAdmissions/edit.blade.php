@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ipdAdmission.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ipd-admissions.update", [$ipdAdmission->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="patient_id">{{ trans('cruds.ipdAdmission.fields.patient') }}</label>
                <select class="form-control select2 {{ $errors->has('patient') ? 'is-invalid' : '' }}" name="patient_id" id="patient_id" required>
                    @foreach($patients as $id => $entry)
                        <option value="{{ $id }}" {{ (old('patient_id') ? old('patient_id') : $ipdAdmission->patient->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('patient'))
                    <div class="invalid-feedback">
                        {{ $errors->first('patient') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.patient_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="doctor_id">{{ trans('cruds.ipdAdmission.fields.doctor') }}</label>
                <select class="form-control select2 {{ $errors->has('doctor') ? 'is-invalid' : '' }}" name="doctor_id" id="doctor_id">
                    @foreach($doctors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('doctor_id') ? old('doctor_id') : $ipdAdmission->doctor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('doctor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.doctor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="admission_date">{{ trans('cruds.ipdAdmission.fields.admission_date') }}</label>
                <input class="form-control date {{ $errors->has('admission_date') ? 'is-invalid' : '' }}" type="text" name="admission_date" id="admission_date" value="{{ old('admission_date', $ipdAdmission->admission_date) }}">
                @if($errors->has('admission_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('admission_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.admission_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="admission_time">{{ trans('cruds.ipdAdmission.fields.admission_time') }}</label>
                <input class="form-control timepicker {{ $errors->has('admission_time') ? 'is-invalid' : '' }}" type="text" name="admission_time" id="admission_time" value="{{ old('admission_time', $ipdAdmission->admission_time) }}">
                @if($errors->has('admission_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('admission_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.admission_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="room_id">{{ trans('cruds.ipdAdmission.fields.room') }}</label>
                <select class="form-control select2 {{ $errors->has('room') ? 'is-invalid' : '' }}" name="room_id" id="room_id">
                    @foreach($rooms as $id => $entry)
                        <option value="{{ $id }}" {{ (old('room_id') ? old('room_id') : $ipdAdmission->room->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('room'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.room_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bed_id">{{ trans('cruds.ipdAdmission.fields.bed') }}</label>
                <select class="form-control select2 {{ $errors->has('bed') ? 'is-invalid' : '' }}" name="bed_id" id="bed_id">
                    @foreach($beds as $id => $entry)
                        <option value="{{ $id }}" {{ (old('bed_id') ? old('bed_id') : $ipdAdmission->bed->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('bed'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bed') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.bed_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="condition_on_admission">{{ trans('cruds.ipdAdmission.fields.condition_on_admission') }}</label>
                <input class="form-control {{ $errors->has('condition_on_admission') ? 'is-invalid' : '' }}" type="text" name="condition_on_admission" id="condition_on_admission" value="{{ old('condition_on_admission', $ipdAdmission->condition_on_admission) }}">
                @if($errors->has('condition_on_admission'))
                    <div class="invalid-feedback">
                        {{ $errors->first('condition_on_admission') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.condition_on_admission_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reason">{{ trans('cruds.ipdAdmission.fields.reason') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('reason') ? 'is-invalid' : '' }}" name="reason" id="reason">{!! old('reason', $ipdAdmission->reason) !!}</textarea>
                @if($errors->has('reason'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reason') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.reason_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.ipdAdmission.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\IpdAdmission::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $ipdAdmission->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attechment">{{ trans('cruds.ipdAdmission.fields.attechment') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attechment') ? 'is-invalid' : '' }}" id="attechment-dropzone">
                </div>
                @if($errors->has('attechment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attechment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.attechment_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="ipd_number">{{ trans('cruds.ipdAdmission.fields.ipd_number') }}</label>
                <input class="form-control {{ $errors->has('ipd_number') ? 'is-invalid' : '' }}" type="text" name="ipd_number" id="ipd_number" value="{{ old('ipd_number', $ipdAdmission->ipd_number) }}">
                @if($errors->has('ipd_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ipd_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdAdmission.fields.ipd_number_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.ipd-admissions.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
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

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $ipdAdmission->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    Dropzone.options.attechmentDropzone = {
    url: '{{ route('admin.ipd-admissions.storeMedia') }}',
    maxFilesize: 20, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20
    },
    success: function (file, response) {
      $('form').find('input[name="attechment"]').remove()
      $('form').append('<input type="hidden" name="attechment" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="attechment"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($ipdAdmission) && $ipdAdmission->attechment)
      var file = {!! json_encode($ipdAdmission->attechment) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="attechment" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection