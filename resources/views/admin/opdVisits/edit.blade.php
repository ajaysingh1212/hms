@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.opdVisit.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.opd-visits.update", [$opdVisit->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="patient_id">{{ trans('cruds.opdVisit.fields.patient') }}</label>
                <select class="form-control select2 {{ $errors->has('patient') ? 'is-invalid' : '' }}" name="patient_id" id="patient_id" required>
                    @foreach($patients as $id => $entry)
                        <option value="{{ $id }}" {{ (old('patient_id') ? old('patient_id') : $opdVisit->patient->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('patient'))
                    <div class="invalid-feedback">
                        {{ $errors->first('patient') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.patient_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="doctor_id">{{ trans('cruds.opdVisit.fields.doctor') }}</label>
                <select class="form-control select2 {{ $errors->has('doctor') ? 'is-invalid' : '' }}" name="doctor_id" id="doctor_id" required>
                    @foreach($doctors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('doctor_id') ? old('doctor_id') : $opdVisit->doctor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('doctor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.doctor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="visit_date">{{ trans('cruds.opdVisit.fields.visit_date') }}</label>
                <input class="form-control date {{ $errors->has('visit_date') ? 'is-invalid' : '' }}" type="text" name="visit_date" id="visit_date" value="{{ old('visit_date', $opdVisit->visit_date) }}">
                @if($errors->has('visit_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('visit_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.visit_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="visit_time">{{ trans('cruds.opdVisit.fields.visit_time') }}</label>
                <input class="form-control timepicker {{ $errors->has('visit_time') ? 'is-invalid' : '' }}" type="text" name="visit_time" id="visit_time" value="{{ old('visit_time', $opdVisit->visit_time) }}">
                @if($errors->has('visit_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('visit_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.visit_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="symptoms">{{ trans('cruds.opdVisit.fields.symptoms') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('symptoms') ? 'is-invalid' : '' }}" name="symptoms" id="symptoms">{!! old('symptoms', $opdVisit->symptoms) !!}</textarea>
                @if($errors->has('symptoms'))
                    <div class="invalid-feedback">
                        {{ $errors->first('symptoms') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.symptoms_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="diagnosis">{{ trans('cruds.opdVisit.fields.diagnosis') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('diagnosis') ? 'is-invalid' : '' }}" name="diagnosis" id="diagnosis">{!! old('diagnosis', $opdVisit->diagnosis) !!}</textarea>
                @if($errors->has('diagnosis'))
                    <div class="invalid-feedback">
                        {{ $errors->first('diagnosis') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.diagnosis_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.opdVisit.fields.notes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{!! old('notes', $opdVisit->notes) !!}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.opdVisit.fields.visit_type') }}</label>
                <select class="form-control {{ $errors->has('visit_type') ? 'is-invalid' : '' }}" name="visit_type" id="visit_type">
                    <option value disabled {{ old('visit_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\OpdVisit::VISIT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('visit_type', $opdVisit->visit_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('visit_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('visit_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.visit_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.opdVisit.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\OpdVisit::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $opdVisit->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attechment">{{ trans('cruds.opdVisit.fields.attechment') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attechment') ? 'is-invalid' : '' }}" id="attechment-dropzone">
                </div>
                @if($errors->has('attechment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attechment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdVisit.fields.attechment_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.opd-visits.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $opdVisit->id ?? 0 }}');
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
    var uploadedAttechmentMap = {}
Dropzone.options.attechmentDropzone = {
    url: '{{ route('admin.opd-visits.storeMedia') }}',
    maxFilesize: 20, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attechment[]" value="' + response.name + '">')
      uploadedAttechmentMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttechmentMap[file.name]
      }
      $('form').find('input[name="attechment[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($opdVisit) && $opdVisit->attechment)
          var files =
            {!! json_encode($opdVisit->attechment) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attechment[]" value="' + file.file_name + '">')
            }
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