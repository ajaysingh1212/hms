@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ipdVital.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ipd-vitals.update", [$ipdVital->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="ipd_id">{{ trans('cruds.ipdVital.fields.ipd') }}</label>
                <select class="form-control select2 {{ $errors->has('ipd') ? 'is-invalid' : '' }}" name="ipd_id" id="ipd_id">
                    @foreach($ipds as $id => $entry)
                        <option value="{{ $id }}" {{ (old('ipd_id') ? old('ipd_id') : $ipdVital->ipd->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ipd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ipd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.ipd_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="date_time">{{ trans('cruds.ipdVital.fields.date_time') }}</label>
                <input class="form-control datetime {{ $errors->has('date_time') ? 'is-invalid' : '' }}" type="text" name="date_time" id="date_time" value="{{ old('date_time', $ipdVital->date_time) }}">
                @if($errors->has('date_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.date_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="temperature">{{ trans('cruds.ipdVital.fields.temperature') }}</label>
                <input class="form-control {{ $errors->has('temperature') ? 'is-invalid' : '' }}" type="text" name="temperature" id="temperature" value="{{ old('temperature', $ipdVital->temperature) }}">
                @if($errors->has('temperature'))
                    <div class="invalid-feedback">
                        {{ $errors->first('temperature') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.temperature_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="pulse">{{ trans('cruds.ipdVital.fields.pulse') }}</label>
                <input class="form-control {{ $errors->has('pulse') ? 'is-invalid' : '' }}" type="text" name="pulse" id="pulse" value="{{ old('pulse', $ipdVital->pulse) }}">
                @if($errors->has('pulse'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pulse') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.pulse_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bp">{{ trans('cruds.ipdVital.fields.bp') }}</label>
                <input class="form-control {{ $errors->has('bp') ? 'is-invalid' : '' }}" type="text" name="bp" id="bp" value="{{ old('bp', $ipdVital->bp) }}">
                @if($errors->has('bp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.bp_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="spo_2">{{ trans('cruds.ipdVital.fields.spo_2') }}</label>
                <input class="form-control {{ $errors->has('spo_2') ? 'is-invalid' : '' }}" type="text" name="spo_2" id="spo_2" value="{{ old('spo_2', $ipdVital->spo_2) }}">
                @if($errors->has('spo_2'))
                    <div class="invalid-feedback">
                        {{ $errors->first('spo_2') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.spo_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="respiration">{{ trans('cruds.ipdVital.fields.respiration') }}</label>
                <input class="form-control {{ $errors->has('respiration') ? 'is-invalid' : '' }}" type="text" name="respiration" id="respiration" value="{{ old('respiration', $ipdVital->respiration) }}">
                @if($errors->has('respiration'))
                    <div class="invalid-feedback">
                        {{ $errors->first('respiration') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.respiration_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.ipdVital.fields.notes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{!! old('notes', $ipdVital->notes) !!}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attechments">{{ trans('cruds.ipdVital.fields.attechments') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attechments') ? 'is-invalid' : '' }}" id="attechments-dropzone">
                </div>
                @if($errors->has('attechments'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attechments') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdVital.fields.attechments_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.ipd-vitals.storeCKEditorImages') }}', true);
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
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

<script>
    Dropzone.options.attechmentsDropzone = {
    url: '{{ route('admin.ipd-vitals.storeMedia') }}',
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
      $('form').find('input[name="attechments"]').remove()
      $('form').append('<input type="hidden" name="attechments" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="attechments"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($ipdVital) && $ipdVital->attechments)
      var file = {!! json_encode($ipdVital->attechments) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="attechments" value="' + file.file_name + '">')
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