@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.appointmentSlot.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.appointment-slots.update", [$appointmentSlot->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="select_doctor_id">{{ trans('cruds.appointmentSlot.fields.select_doctor') }}</label>
                <select class="form-control select2 {{ $errors->has('select_doctor') ? 'is-invalid' : '' }}" name="select_doctor_id" id="select_doctor_id" required>
                    @foreach($select_doctors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('select_doctor_id') ? old('select_doctor_id') : $appointmentSlot->select_doctor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('select_doctor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('select_doctor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointmentSlot.fields.select_doctor_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="select_time">{{ trans('cruds.appointmentSlot.fields.select_time') }}</label>
                <input class="form-control timepicker {{ $errors->has('select_time') ? 'is-invalid' : '' }}" type="text" name="select_time" id="select_time" value="{{ old('select_time', $appointmentSlot->select_time) }}" required>
                @if($errors->has('select_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('select_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointmentSlot.fields.select_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.appointmentSlot.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $appointmentSlot->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointmentSlot.fields.description_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.appointment-slots.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $appointmentSlot->id ?? 0 }}');
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