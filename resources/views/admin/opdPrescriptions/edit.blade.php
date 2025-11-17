@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.opdPrescription.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.opd-prescriptions.update", [$opdPrescription->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="opd_id">{{ trans('cruds.opdPrescription.fields.opd') }}</label>
                <select class="form-control select2 {{ $errors->has('opd') ? 'is-invalid' : '' }}" name="opd_id" id="opd_id">
                    @foreach($opds as $id => $entry)
                        <option value="{{ $id }}" {{ (old('opd_id') ? old('opd_id') : $opdPrescription->opd->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('opd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('opd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdPrescription.fields.opd_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="medicines">{{ trans('cruds.opdPrescription.fields.medicine') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('medicines') ? 'is-invalid' : '' }}" name="medicines[]" id="medicines" multiple>
                    @foreach($medicines as $id => $medicine)
                        <option value="{{ $id }}" {{ (in_array($id, old('medicines', [])) || $opdPrescription->medicines->contains($id)) ? 'selected' : '' }}>{{ $medicine }}</option>
                    @endforeach
                </select>
                @if($errors->has('medicines'))
                    <div class="invalid-feedback">
                        {{ $errors->first('medicines') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdPrescription.fields.medicine_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="dosage">{{ trans('cruds.opdPrescription.fields.dosage') }}</label>
                <input class="form-control {{ $errors->has('dosage') ? 'is-invalid' : '' }}" type="text" name="dosage" id="dosage" value="{{ old('dosage', $opdPrescription->dosage) }}">
                @if($errors->has('dosage'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dosage') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdPrescription.fields.dosage_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="duration">{{ trans('cruds.opdPrescription.fields.duration') }}</label>
                <input class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}" type="text" name="duration" id="duration" value="{{ old('duration', $opdPrescription->duration) }}">
                @if($errors->has('duration'))
                    <div class="invalid-feedback">
                        {{ $errors->first('duration') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdPrescription.fields.duration_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="instructions">{{ trans('cruds.opdPrescription.fields.instructions') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('instructions') ? 'is-invalid' : '' }}" name="instructions" id="instructions">{!! old('instructions', $opdPrescription->instructions) !!}</textarea>
                @if($errors->has('instructions'))
                    <div class="invalid-feedback">
                        {{ $errors->first('instructions') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdPrescription.fields.instructions_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attechment">{{ trans('cruds.opdPrescription.fields.attechment') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attechment') ? 'is-invalid' : '' }}" id="attechment-dropzone">
                </div>
                @if($errors->has('attechment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attechment') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdPrescription.fields.attechment_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.opd-prescriptions.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $opdPrescription->id ?? 0 }}');
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
    url: '{{ route('admin.opd-prescriptions.storeMedia') }}',
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
@if(isset($opdPrescription) && $opdPrescription->attechment)
          var files =
            {!! json_encode($opdPrescription->attechment) !!}
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