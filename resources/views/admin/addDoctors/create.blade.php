@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.addDoctor.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.add-doctors.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="select_department_id">{{ trans('cruds.addDoctor.fields.select_department') }}</label>
                <select class="form-control select2 {{ $errors->has('select_department') ? 'is-invalid' : '' }}" name="select_department_id" id="select_department_id">
                    @foreach($select_departments as $id => $entry)
                        <option value="{{ $id }}" {{ old('select_department_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('select_department'))
                    <div class="invalid-feedback">
                        {{ $errors->first('select_department') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.addDoctor.fields.select_department_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="doctor_name">{{ trans('cruds.addDoctor.fields.doctor_name') }}</label>
                <input class="form-control {{ $errors->has('doctor_name') ? 'is-invalid' : '' }}" type="text" name="doctor_name" id="doctor_name" value="{{ old('doctor_name', '') }}" required>
                @if($errors->has('doctor_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.addDoctor.fields.doctor_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.addDoctor.fields.available_days') }}</label>
                @foreach(App\Models\AddDoctor::AVAILABLE_DAYS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('available_days') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="available_days_{{ $key }}" name="available_days" value="{{ $key }}" {{ old('available_days', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="available_days_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('available_days'))
                    <div class="invalid-feedback">
                        {{ $errors->first('available_days') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.addDoctor.fields.available_days_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="appointment_slot_duration">{{ trans('cruds.addDoctor.fields.appointment_slot_duration') }}</label>
                <input class="form-control {{ $errors->has('appointment_slot_duration') ? 'is-invalid' : '' }}" type="text" name="appointment_slot_duration" id="appointment_slot_duration" value="{{ old('appointment_slot_duration', '') }}">
                @if($errors->has('appointment_slot_duration'))
                    <div class="invalid-feedback">
                        {{ $errors->first('appointment_slot_duration') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.addDoctor.fields.appointment_slot_duration_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="max_patients_per_day">{{ trans('cruds.addDoctor.fields.max_patients_per_day') }}</label>
                <input class="form-control {{ $errors->has('max_patients_per_day') ? 'is-invalid' : '' }}" type="text" name="max_patients_per_day" id="max_patients_per_day" value="{{ old('max_patients_per_day', '') }}">
                @if($errors->has('max_patients_per_day'))
                    <div class="invalid-feedback">
                        {{ $errors->first('max_patients_per_day') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.addDoctor.fields.max_patients_per_day_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="doctor_fee">{{ trans('cruds.addDoctor.fields.doctor_fee') }}</label>
                <input class="form-control {{ $errors->has('doctor_fee') ? 'is-invalid' : '' }}" type="number" name="doctor_fee" id="doctor_fee" value="{{ old('doctor_fee', '') }}" step="0.01">
                @if($errors->has('doctor_fee'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor_fee') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.addDoctor.fields.doctor_fee_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.addDoctor.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.addDoctor.fields.description_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.add-doctors.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $addDoctor->id ?? 0 }}');
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

@endsection