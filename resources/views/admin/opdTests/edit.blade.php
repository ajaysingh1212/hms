@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.opdTest.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.opd-tests.update", [$opdTest->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="opd_id">{{ trans('cruds.opdTest.fields.opd') }}</label>
                <select class="form-control select2 {{ $errors->has('opd') ? 'is-invalid' : '' }}" name="opd_id" id="opd_id" required>
                    @foreach($opds as $id => $entry)
                        <option value="{{ $id }}" {{ (old('opd_id') ? old('opd_id') : $opdTest->opd->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('opd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('opd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdTest.fields.opd_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tests">{{ trans('cruds.opdTest.fields.test') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('tests') ? 'is-invalid' : '' }}" name="tests[]" id="tests" multiple>
                    @foreach($tests as $id => $test)
                        <option value="{{ $id }}" {{ (in_array($id, old('tests', [])) || $opdTest->tests->contains($id)) ? 'selected' : '' }}>{{ $test }}</option>
                    @endforeach
                </select>
                @if($errors->has('tests'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tests') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdTest.fields.test_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.opdTest.fields.notes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{!! old('notes', $opdTest->notes) !!}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdTest.fields.notes_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.opd-tests.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $opdTest->id ?? 0 }}');
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