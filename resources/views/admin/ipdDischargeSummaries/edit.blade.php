@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ipdDischargeSummary.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ipd-discharge-summaries.update", [$ipdDischargeSummary->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="ipd_id">{{ trans('cruds.ipdDischargeSummary.fields.ipd') }}</label>
                <select class="form-control select2 {{ $errors->has('ipd') ? 'is-invalid' : '' }}" name="ipd_id" id="ipd_id">
                    @foreach($ipds as $id => $entry)
                        <option value="{{ $id }}" {{ (old('ipd_id') ? old('ipd_id') : $ipdDischargeSummary->ipd->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ipd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ipd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdDischargeSummary.fields.ipd_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="discharge_date">{{ trans('cruds.ipdDischargeSummary.fields.discharge_date') }}</label>
                <input class="form-control date {{ $errors->has('discharge_date') ? 'is-invalid' : '' }}" type="text" name="discharge_date" id="discharge_date" value="{{ old('discharge_date', $ipdDischargeSummary->discharge_date) }}">
                @if($errors->has('discharge_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discharge_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdDischargeSummary.fields.discharge_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="condition_on_discharge">{{ trans('cruds.ipdDischargeSummary.fields.condition_on_discharge') }}</label>
                <input class="form-control {{ $errors->has('condition_on_discharge') ? 'is-invalid' : '' }}" type="text" name="condition_on_discharge" id="condition_on_discharge" value="{{ old('condition_on_discharge', $ipdDischargeSummary->condition_on_discharge) }}">
                @if($errors->has('condition_on_discharge'))
                    <div class="invalid-feedback">
                        {{ $errors->first('condition_on_discharge') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdDischargeSummary.fields.condition_on_discharge_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="summary">{{ trans('cruds.ipdDischargeSummary.fields.summary') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('summary') ? 'is-invalid' : '' }}" name="summary" id="summary">{!! old('summary', $ipdDischargeSummary->summary) !!}</textarea>
                @if($errors->has('summary'))
                    <div class="invalid-feedback">
                        {{ $errors->first('summary') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdDischargeSummary.fields.summary_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="advice">{{ trans('cruds.ipdDischargeSummary.fields.advice') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('advice') ? 'is-invalid' : '' }}" name="advice" id="advice">{!! old('advice', $ipdDischargeSummary->advice) !!}</textarea>
                @if($errors->has('advice'))
                    <div class="invalid-feedback">
                        {{ $errors->first('advice') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdDischargeSummary.fields.advice_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="followup_date">{{ trans('cruds.ipdDischargeSummary.fields.followup_date') }}</label>
                <input class="form-control date {{ $errors->has('followup_date') ? 'is-invalid' : '' }}" type="text" name="followup_date" id="followup_date" value="{{ old('followup_date', $ipdDischargeSummary->followup_date) }}">
                @if($errors->has('followup_date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('followup_date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdDischargeSummary.fields.followup_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attechments">{{ trans('cruds.ipdDischargeSummary.fields.attechments') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attechments') ? 'is-invalid' : '' }}" id="attechments-dropzone">
                </div>
                @if($errors->has('attechments'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attechments') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdDischargeSummary.fields.attechments_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.ipd-discharge-summaries.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $ipdDischargeSummary->id ?? 0 }}');
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
    url: '{{ route('admin.ipd-discharge-summaries.storeMedia') }}',
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
@if(isset($ipdDischargeSummary) && $ipdDischargeSummary->attechments)
      var file = {!! json_encode($ipdDischargeSummary->attechments) !!}
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