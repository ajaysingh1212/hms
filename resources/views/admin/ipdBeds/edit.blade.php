@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.ipdBed.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.ipd-beds.update", [$ipdBed->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="room_id">{{ trans('cruds.ipdBed.fields.room') }}</label>
                <select class="form-control select2 {{ $errors->has('room') ? 'is-invalid' : '' }}" name="room_id" id="room_id" required>
                    @foreach($rooms as $id => $entry)
                        <option value="{{ $id }}" {{ (old('room_id') ? old('room_id') : $ipdBed->room->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('room'))
                    <div class="invalid-feedback">
                        {{ $errors->first('room') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBed.fields.room_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bed_no">{{ trans('cruds.ipdBed.fields.bed_no') }}</label>
                <input class="form-control {{ $errors->has('bed_no') ? 'is-invalid' : '' }}" type="text" name="bed_no" id="bed_no" value="{{ old('bed_no', $ipdBed->bed_no) }}">
                @if($errors->has('bed_no'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bed_no') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBed.fields.bed_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="charges_per_day">{{ trans('cruds.ipdBed.fields.charges_per_day') }}</label>
                <input class="form-control {{ $errors->has('charges_per_day') ? 'is-invalid' : '' }}" type="number" name="charges_per_day" id="charges_per_day" value="{{ old('charges_per_day', $ipdBed->charges_per_day) }}" step="0.01">
                @if($errors->has('charges_per_day'))
                    <div class="invalid-feedback">
                        {{ $errors->first('charges_per_day') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBed.fields.charges_per_day_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.ipdBed.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\IpdBed::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $ipdBed->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBed.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.ipdBed.fields.notes') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{!! old('notes', $ipdBed->notes) !!}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.ipdBed.fields.notes_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.ipd-beds.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $ipdBed->id ?? 0 }}');
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