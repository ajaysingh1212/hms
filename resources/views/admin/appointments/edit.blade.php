@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.appointment.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.appointments.update", [$appointment->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="patient_number">{{ trans('cruds.appointment.fields.patient_number') }}</label>
                <input class="form-control {{ $errors->has('patient_number') ? 'is-invalid' : '' }}" type="text" name="patient_number" id="patient_number" value="{{ old('patient_number', $appointment->patient_number) }}">
                @if($errors->has('patient_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('patient_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.patient_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="department_id">{{ trans('cruds.appointment.fields.department') }}</label>
                <select class="form-control select2 {{ $errors->has('department') ? 'is-invalid' : '' }}" name="department_id" id="department_id" required>
                    @foreach($departments as $id => $entry)
                        <option value="{{ $id }}" {{ (old('department_id') ? old('department_id') : $appointment->department->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('department'))
                    <div class="invalid-feedback">
                        {{ $errors->first('department') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.department_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="doctor_id">{{ trans('cruds.appointment.fields.doctor') }}</label>
                <select class="form-control select2 {{ $errors->has('doctor') ? 'is-invalid' : '' }}" name="doctor_id" id="doctor_id" required>
                    @foreach($doctors as $id => $entry)
                        <option value="{{ $id }}" {{ (old('doctor_id') ? old('doctor_id') : $appointment->doctor->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('doctor'))
                    <div class="invalid-feedback">
                        {{ $errors->first('doctor') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.doctor_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="available_slots_id">{{ trans('cruds.appointment.fields.available_slots') }}</label>
                <select class="form-control select2 {{ $errors->has('available_slots') ? 'is-invalid' : '' }}" name="available_slots_id" id="available_slots_id">
                    @foreach($available_slots as $id => $entry)
                        <option value="{{ $id }}" {{ (old('available_slots_id') ? old('available_slots_id') : $appointment->available_slots->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('available_slots'))
                    <div class="invalid-feedback">
                        {{ $errors->first('available_slots') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.available_slots_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="patient_name">{{ trans('cruds.appointment.fields.patient_name') }}</label>
                <input class="form-control {{ $errors->has('patient_name') ? 'is-invalid' : '' }}" type="text" name="patient_name" id="patient_name" value="{{ old('patient_name', $appointment->patient_name) }}" required>
                @if($errors->has('patient_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('patient_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.patient_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="mobile_number">{{ trans('cruds.appointment.fields.mobile_number') }}</label>
                <input class="form-control {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}" type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $appointment->mobile_number) }}" required>
                @if($errors->has('mobile_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mobile_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.mobile_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="date">{{ trans('cruds.appointment.fields.date') }}</label>
                <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $appointment->date) }}" required>
                @if($errors->has('date'))
                    <div class="invalid-feedback">
                        {{ $errors->first('date') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reason_for_visit">{{ trans('cruds.appointment.fields.reason_for_visit') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('reason_for_visit') ? 'is-invalid' : '' }}" name="reason_for_visit" id="reason_for_visit">{!! old('reason_for_visit', $appointment->reason_for_visit) !!}</textarea>
                @if($errors->has('reason_for_visit'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reason_for_visit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.reason_for_visit_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.appointment.fields.appointment_type') }}</label>
                <select class="form-control {{ $errors->has('appointment_type') ? 'is-invalid' : '' }}" name="appointment_type" id="appointment_type">
                    <option value disabled {{ old('appointment_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Appointment::APPOINTMENT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('appointment_type', $appointment->appointment_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('appointment_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('appointment_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.appointment_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.appointment.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Appointment::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $appointment->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.appointment.fields.status_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.appointments.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $appointment->id ?? 0 }}');
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