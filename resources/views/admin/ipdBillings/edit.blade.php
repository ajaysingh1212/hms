@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ipdBilling.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ipd-billings.update", [$ipdBilling->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="ipd_id">{{ trans('cruds.ipdBilling.fields.ipd') }}</label>
                <select class="form-control select2 {{ $errors->has('ipd') ? 'is-invalid' : '' }}" name="ipd_id" id="ipd_id">
                    @foreach($ipds as $id => $entry)
                        <option value="{{ $id }}" {{ (old('ipd_id') ? old('ipd_id') : $ipdBilling->ipd->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('ipd'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ipd') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.ipd_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="other_charges">{{ trans('cruds.ipdBilling.fields.other_charges') }}</label>
                <input class="form-control {{ $errors->has('other_charges') ? 'is-invalid' : '' }}" type="number" name="other_charges" id="other_charges" value="{{ old('other_charges', $ipdBilling->other_charges) }}" step="0.01">
                @if($errors->has('other_charges'))
                    <div class="invalid-feedback">
                        {{ $errors->first('other_charges') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.other_charges_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="total">{{ trans('cruds.ipdBilling.fields.total') }}</label>
                <input class="form-control {{ $errors->has('total') ? 'is-invalid' : '' }}" type="number" name="total" id="total" value="{{ old('total', $ipdBilling->total) }}" step="0.01">
                @if($errors->has('total'))
                    <div class="invalid-feedback">
                        {{ $errors->first('total') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.total_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.ipdBilling.fields.discount_type') }}</label>
                <select class="form-control {{ $errors->has('discount_type') ? 'is-invalid' : '' }}" name="discount_type" id="discount_type">
                    <option value disabled {{ old('discount_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\IpdBilling::DISCOUNT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('discount_type', $ipdBilling->discount_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('discount_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('discount_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.discount_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="paid">{{ trans('cruds.ipdBilling.fields.paid') }}</label>
                <input class="form-control {{ $errors->has('paid') ? 'is-invalid' : '' }}" type="number" name="paid" id="paid" value="{{ old('paid', $ipdBilling->paid) }}" step="0.01">
                @if($errors->has('paid'))
                    <div class="invalid-feedback">
                        {{ $errors->first('paid') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.paid_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="due">{{ trans('cruds.ipdBilling.fields.due') }}</label>
                <input class="form-control {{ $errors->has('due') ? 'is-invalid' : '' }}" type="number" name="due" id="due" value="{{ old('due', $ipdBilling->due) }}" step="0.01">
                @if($errors->has('due'))
                    <div class="invalid-feedback">
                        {{ $errors->first('due') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.due_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.ipdBilling.fields.payment_type') }}</label>
                <select class="form-control {{ $errors->has('payment_type') ? 'is-invalid' : '' }}" name="payment_type" id="payment_type">
                    <option value disabled {{ old('payment_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\IpdBilling::PAYMENT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_type', $ipdBilling->payment_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.payment_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.ipdBilling.fields.notes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{!! old('notes', $ipdBilling->notes) !!}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="attechments">{{ trans('cruds.ipdBilling.fields.attechments') }}</label>
                <div class="needsclick dropzone {{ $errors->has('attechments') ? 'is-invalid' : '' }}" id="attechments-dropzone">
                </div>
                @if($errors->has('attechments'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attechments') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBilling.fields.attechments_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.ipd-billings.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $ipdBilling->id ?? 0 }}');
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
    var uploadedAttechmentsMap = {}
Dropzone.options.attechmentsDropzone = {
    url: '{{ route('admin.ipd-billings.storeMedia') }}',
    maxFilesize: 20, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attechments[]" value="' + response.name + '">')
      uploadedAttechmentsMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttechmentsMap[file.name]
      }
      $('form').find('input[name="attechments[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ipdBilling) && $ipdBilling->attechments)
          var files =
            {!! json_encode($ipdBilling->attechments) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attechments[]" value="' + file.file_name + '">')
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