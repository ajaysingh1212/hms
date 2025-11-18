@extends('layouts.admin')

@section('styles')
<style>
    .form-section {
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0px 0px 12px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }
    .form-title {
        font-weight: bold;
        font-size: 20px;
        margin-bottom: 15px;
        color: #2b4b8b;
    }
</style>
@endsection

@section('content')

<div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
        <strong>Create New Doctor</strong>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.add-doctors.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Section: Login Credentials --}}
            <div class="form-section">
                <div class="form-title">Doctor Login Details</div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="required">Doctor Login Name</label>
                        <input type="text" name="login_name" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="required">Doctor Email (Login ID)</label>
                        <input type="email" name="login_email" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="required">Doctor Password</label>
                        <input type="password" name="login_password" class="form-control" required>
                    </div>
                </div>
            </div>

            {{-- Section: Basic Doctor Info --}}
            <div class="form-section">
                <div class="form-title">Basic Information</div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="required">Department</label>
                        <select class="form-control select2" name="select_department_id" required>
                            @foreach($select_departments as $id => $entry)
                                <option value="{{ $id }}">{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="required">Doctor Name</label>
                        <input type="text" name="doctor_name" class="form-control" required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Doctor Fee</label>
                        <input type="number" name="doctor_fee" class="form-control" step="0.01">
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Appointment Slot Duration (Minutes)</label>
                        <input type="number" name="appointment_slot_duration" class="form-control">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Max Patients / Day</label>
                        <input type="number" name="max_patients_per_day" class="form-control">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Qualification</label>
                        <input type="text" name="qualifications" class="form-control">
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Experience (Years)</label>
                        <input type="number" name="experience" class="form-control">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control">
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Alternate Phone</label>
                        <input type="text" name="phone_alt" class="form-control">
                    </div>
                </div>

            </div>


            {{-- Section: Available Days --}}
            <div class="form-section">
                <div class="form-title">Available Days</div>

                <div class="row">
                    @foreach(App\Models\AddDoctor::AVAILABLE_DAYS_RADIO as $key => $label)
                        <div class="col-md-3">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="available_days[]" value="{{ $key }}" class="form-check-input" id="day_{{ $key }}">
                                <label class="form-check-label" for="day_{{ $key }}">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


            {{-- Section: Description --}}
            <div class="form-section">
                <div class="form-title">Doctor Description</div>

                <div class="form-group">
                    <textarea class="form-control ckeditor" name="description" rows="5"></textarea>
                </div>
            </div>


            <button class="btn btn-success btn-lg" type="submit">
                Save Doctor
            </button>

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
                    return loader.file.then(function (file) {
                        return new Promise(function(resolve, reject) {
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', '{{ route('admin.add-doctors.storeCKEditorImages') }}', true);
                            xhr.setRequestHeader('x-csrf-token', window._token);
                            xhr.setRequestHeader('Accept', 'application/json');
                            xhr.responseType = 'json';

                            var genericErrorText = `Couldn't upload file: ${ file.name }.`;

                            xhr.addEventListener('error', function() { reject(genericErrorText) });
                            xhr.addEventListener('abort', function() { reject() });
                            xhr.addEventListener('load', function() {
                                var response = xhr.response;

                                if (!response || xhr.status !== 201) {
                                    return reject(response && response.message ?
                                        `${genericErrorText}\n${xhr.status} ${response.message}` :
                                        `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                                }

                                $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');
                                resolve({ default: response.url });
                            });

                            var data = new FormData();
                            data.append('upload', file);
                            data.append('crud_id', 0);
                            xhr.send(data);
                        });
                    })
                }
            };
        }
    }

    var editors = document.querySelectorAll('.ckeditor');
    editors.forEach(function(editorElement) {
        ClassicEditor.create(editorElement, { extraPlugins: [SimpleUploadAdapter] });
    });
});
</script>
@endsection
