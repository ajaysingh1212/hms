@extends('layouts.admin')
@section('content')

<style>
/* small creative styles */
.card-creative { border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); overflow: hidden; }
.card-creative .card-header { background: linear-gradient(90deg,#6f42c1,#1f8ef1); color:#fff; font-weight:600; }
.info-pill { display:inline-block; padding:4px 10px; border-radius:20px; background:rgba(0,0,0,0.04); font-size:12px; }
.small-muted { font-size:13px; color:#6c757d; }
.meta { font-size:13px; color:#495057; }
.room-beds-table td, .room-beds-table th { vertical-align: middle; }
.upload-card { border-radius:10px; border:2px dashed #e9ecef; background: linear-gradient(180deg,#ffffff,#f8f9fa); padding:16px; }
</style>

<div class="card card-creative">
    <div class="card-header">
        <i class="fas fa-procedures mr-2"></i> {{ trans('global.create') }} {{ trans('cruds.ipdAdmission.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.ipd-admissions.store') }}" enctype="multipart/form-data" id="ipd-form">
            @csrf

            {{-- TOP ROW: patient / doctor / ipd number --}}
            <div class="row mb-3">
                <div class="col-lg-4">
                    <label class="required">{{ trans('cruds.ipdAdmission.fields.patient') }}</label>
                    <select id="patient_id" name="patient_id" class="form-control select2" required>
                        <option value="">{{ trans('global.pleaseSelect') }}</option>
                        @foreach($patients as $id => $entry)
                            <option value="{{ $id }}" {{ old('patient_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4">
                    <label>{{ trans('cruds.ipdAdmission.fields.doctor') }}</label>
                    <select id="doctor_id" name="doctor_id" class="form-control select2">
                        <option value="">{{ trans('global.pleaseSelect') }}</option>
                        @foreach($doctors as $id => $entry)
                            <option value="{{ $id }}" {{ old('doctor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-4">
                    <label>{{ trans('cruds.ipdAdmission.fields.ipd_number') }}</label>
                    <input type="text" id="ipd_number" name="ipd_number" class="form-control" value="{{ old('ipd_number') }}" readonly>
                </div>
            </div>

            {{-- 3 cards: appointment / doctor / summary --}}
            <div class="row mb-4">
                <div class="col-lg-4">
                    <div id="card-appointment" class="card card-creative" style="display:none;">
                        <div class="card-header"> <i class="fas fa-calendar-check mr-2"></i> Appointment</div>
                        <div class="card-body">
                            <h5 id="a_patient" class="mb-1"></h5>
                            <div class="small-muted"><i class="fas fa-phone-alt"></i> <span id="a_mobile"></span></div>
                            <div class="mt-2">
                                <div class="meta"><strong>Date:</strong> <span id="a_date"></span></div>
                                <div class="meta"><strong>Type:</strong> <span id="a_type"></span></div>
                                <div class="meta"><strong>Status:</strong> <span id="a_status"></span></div>
                                <div class="mt-2"><strong>Reason:</strong><div id="a_reason"></div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div id="card-doctor" class="card card-creative" style="display:none;">
                        <div class="card-header"><i class="fas fa-user-md mr-2"></i> Doctor</div>
                        <div class="card-body">
                            <h5 id="d_name" class="mb-1"></h5>
                            <div class="small-muted" id="d_department"></div>
                            <div class="row mt-2">
                                <div class="col-6"><strong>Fee:</strong> ₹ <span id="d_fee"></span></div>
                                <div class="col-6"><strong>Exp:</strong> <span id="d_exp"></span> yrs</div>
                            </div>
                            <div class="mt-2"><strong>Qualif:</strong> <div id="d_qual"></div></div>
                            <div class="mt-2 small-muted"><i class="fas fa-phone"></i> <span id="d_phone"></span> &nbsp; <span id="d_phone_alt"></span></div>
                            <div class="mt-2"><strong>Avail Days:</strong> <span id="d_days" class="info-pill"></span></div>
                            <div class="mt-2"><small id="d_desc" class="text-muted"></small></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div id="card-summary" class="card card-creative" style="display:none;">
                        <div class="card-header"><i class="fas fa-info-circle mr-2"></i> Summary</div>
                        <div class="card-body">
                            <div class="meta"><strong>Appointment ID:</strong> <span id="s_appid"></span></div>
                            <div class="meta"><strong>Patient No:</strong> <span id="s_pno"></span></div>
                            <div class="meta"><strong>Booked On:</strong> <span id="s_created"></span></div>
                            <div class="mt-2"><strong>Doctor Slot:</strong><div id="s_slot"></div></div>
                            <div class="mt-2"><strong>Available Days (appt):</strong> <span id="s_days"></span></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form fields rows (3 per row) --}}
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="admission_date">{{ trans('cruds.ipdAdmission.fields.admission_date') }}</label>
                        <input class="form-control date" type="text" name="admission_date" id="admission_date" value="{{ old('admission_date') }}">
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="admission_time">{{ trans('cruds.ipdAdmission.fields.admission_time') }}</label>
                        <input class="form-control timepicker" type="text" name="admission_time" id="admission_time" value="{{ old('admission_time') }}">
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="room_id">{{ trans('cruds.ipdAdmission.fields.room') }}</label>
                        <select id="room_id" name="room_id" class="form-control select2">
                            <option value="">{{ trans('global.pleaseSelect') }}</option>
                            @foreach($rooms as $id => $entry)
                                <option value="{{ $id }}" {{ old('room_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            {{-- Room card (shows when room selected) --}}
            <div id="room-card" class="card card-creative" style="display:none;">
            <div class="card-header"><i class="fas fa-door-open mr-2"></i> Room Details</div>
            <div class="card-body">

                <div class="row">
                    <div class="col-lg-3"><strong>Room No:</strong> <span id="r_no"></span></div>
                    <div class="col-lg-3"><strong>Ward:</strong> <span id="r_ward"></span></div>
                    <div class="col-lg-3"><strong>Charges/Day:</strong> ₹ <span id="r_charges"></span></div>
                    <div class="col-lg-3"><strong>Status:</strong> <span id="r_status"></span></div>
                </div>

                <div class="mt-3">
                    <h6>Available Beds</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped room-beds-table">
                            <thead>
                                <tr>
                                    <th>Bed No</th>
                                    <th>Charges/Day</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="beds-list">
                                {{-- Filled by AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

            {{-- next row: bed / condition / status --}}
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="bed_id">{{ trans('cruds.ipdAdmission.fields.bed') }}</label>
                        <select id="bed_id" name="bed_id" class="form-control select2">
                            <option value="">{{ trans('global.pleaseSelect') }}</option>
                            {{-- filled by ajax when room selected --}}
                        </select>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="condition_on_admission">{{ trans('cruds.ipdAdmission.fields.condition_on_admission') }}</label>
                        <input class="form-control" type="text" name="condition_on_admission" id="condition_on_admission" value="{{ old('condition_on_admission', '') }}">
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        <label>{{ trans('cruds.ipdAdmission.fields.status') }}</label>
                        <select class="form-control" name="status" id="status">
                            <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\IpdAdmission::STATUS_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('status', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Reason (full width) --}}
            <div class="row">
                <div class="col-lg-12">
                    <label for="reason">{{ trans('cruds.ipdAdmission.fields.reason') }}</label>
                    <textarea class="form-control ckeditor" name="reason" id="reason">{!! old('reason') !!}</textarea>
                </div>
            </div>

            {{-- Attachment (creative) --}}
            <div class="row mt-3">
                <div class="col-lg-12">
                    <label>{{ trans('cruds.ipdAdmission.fields.attechment') }}</label>
                    <div class="upload-card">
                        <div class="d-flex align-items-center">
                            <div style="font-size:28px; margin-right:12px;">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Upload attachments</h6>
                                <small class="text-muted">Multiple files allowed — PDFs, images, docs</small>
                            </div>
                        </div>

                        <div class="needsclick dropzone mt-3" id="attechment-dropzone"></div>
                    </div>
                </div>
            </div>

            

            {{-- submit --}}
            <div class="row mt-4">
                <div class="col-lg-12 text-right">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save mr-1"></i> Save</button>
                </div>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {
    // CKEditor setup (kept original logic)
    function SimpleUploadAdapter(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
            return {
                upload: function() {
                    return loader.file.then(function (file) {
                        return new Promise(function(resolve, reject) {
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', '{{ route('admin.ipd-admissions.storeCKEditorImages') }}', true);
                            xhr.setRequestHeader('x-csrf-token', window._token);
                            xhr.setRequestHeader('Accept', 'application/json');
                            xhr.responseType = 'json';
                            var genericErrorText = `Couldn't upload file: ${ file.name }.` ;
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
                            var data = new FormData();
                            data.append('upload', file);
                            data.append('crud_id', '{{ $ipdAdmission->id ?? 0 }}');
                            xhr.send(data);
                        });
                    })
                }
            };
        }
    }

    Array.from(document.querySelectorAll('.ckeditor')).forEach(function(el) {
        ClassicEditor.create(el, { extraPlugins: [SimpleUploadAdapter] }).catch( function(e) { console.error(e); } );
    });

    // Generate random IPD on patient select + fetch appointment & doctor & summary
    $('#patient_id').on('change', function() {
        var pid = $(this).val();
        if (!pid) {
            $('#card-appointment,#card-doctor,#card-summary').hide();
            $('#ipd_number').val('');
            return;
        }

        // generate random 8-digit ipd number
        var rand = Math.floor(10000000 + Math.random() * 90000000);
        $('#ipd_number').val('IPD-' + rand);

        $.ajax({
            url: '{{ route("admin.ipd-admissions.getAppointmentDetails") }}',
            method: 'GET',
            data: { patient_id: pid },
            success: function(res) {
                if (!res || !res.appointment) {
                    $('#card-appointment,#card-doctor,#card-summary').hide();
                    return;
                }

                // Appointment card
                var a = res.appointment;
                $('#a_patient').text(a.patient_name || '-');
                $('#a_mobile').text(a.mobile_number || '-');
                $('#a_date').text(a.date || '-');
                $('#a_type').text(a.appointment_type || '-');
                $('#a_status').text(a.status || '-');
                $('#a_reason').text(a.reason_for_visit || '-');
                $('#card-appointment').fadeIn(200);

                // Doctor card
                if (res.doctor) {
                    $('#d_name').text(res.doctor.name || '-');
                    $('#d_department').text(res.doctor.department_name || '-');
                    $('#d_fee').text(res.doctor.doctor_fee || '0');
                    $('#d_exp').text(res.doctor.experience || '-');
                    $('#d_qual').text(res.doctor.qualifications || '-');
                    $('#d_phone').text(res.doctor.phone || '-');
                    $('#d_phone_alt').text(res.doctor.phone_alt ? ' / ' + res.doctor.phone_alt : '');
                    // available days array -> join
                    try {
                        var days = res.doctor.available_days;
                        if (Array.isArray(days)) {
                            $('#d_days').text(days.join(', '));
                        } else {
                            $('#d_days').text(days || '-');
                        }
                    } catch(e) { $('#d_days').text('-'); }
                    $('#d_desc').text(res.doctor.description || '');
                    $('#card-doctor').fadeIn(200);

                    // auto select doctor in doctor dropdown if exists
                    var dId = res.doctor.id;
                    if ($('#doctor_id option[value="' + dId + '"]').length) {
                        $('#doctor_id').val(dId).trigger('change');
                    } else {
                        $('#doctor_id').append($('<option>', { value: dId, text: res.doctor.name })).val(dId).trigger('change');
                    }
                } else {
                    $('#card-doctor').hide();
                }

                // Summary card
                if (res.summary) {
                    $('#s_appid').text(res.summary.appointment_id || '-');
                    $('#s_pno').text(res.summary.patient_number || '-');
                    $('#s_created').text(res.summary.created_at || '-');
                    // doctor_slot print nicely
                    var slot = res.summary.doctor_slot;
                    if (slot && Array.isArray(slot)) {
                        $('#s_slot').html(slot.join(', '));
                    } else {
                        $('#s_slot').text(slot || '-');
                    }
                    // appt available days
                    var adays = res.summary.available_days;
                    $('#s_days').text(Array.isArray(adays) ? adays.join(', ') : (adays || '-'));
                    $('#card-summary').fadeIn(200);
                } else {
                    $('#card-summary').hide();
                }
            },
            error: function(xhr) {
                console.error(xhr);
                $('#card-appointment,#card-doctor,#card-summary').hide();
            }
        });
    });

    
    // Room -> fetch available beds and show room card
    $('#room_id').on('change', function() {
        var rid = $(this).val();
        if (!rid) {
            $('#room-card').hide();
            $('#bed_id').html('<option value="">{{ trans("global.pleaseSelect") }}</option>');
            return;
        }

        $.ajax({
            url: '{{ route("admin.ipd-admissions.getRoomDetails") }}',
            method: 'GET',
            data: { room_id: rid },
            success: function(res) {
                if (!res || !res.room) {
                    $('#room-card').hide();
                    return;
                }

                // Fill room details in card
                $('#r_no').text(res.room.room_no || '-');
                $('#r_ward').text(res.room.ward_type || '-');
                $('#r_charges').text(res.room.charges_per_day || '-');
                $('#r_status').text(res.room.status || '-');

                // Beds
                let beds = res.beds || [];
                let tableHtml = '';
                let bedDropdown = '<option value="">Select Bed</option>';

                if (beds.length === 0) {
                    tableHtml = `<tr>
                        <td colspan="4" class="text-center text-muted">No available beds in this room</td>
                    </tr>`;
                } else {
                    beds.forEach(b => {
                        tableHtml += `
                            <tr>
                                <td>${b.bed_no}</td>
                                <td>₹ ${b.charges_per_day}</td>
                                <td>${b.status}</td>
                                <td>${b.notes ?? '-'}</td>
                            </tr>
                        `;

                        bedDropdown += `<option value="${b.id}">${b.bed_no} — ₹${b.charges_per_day}</option>`;
                    });
                }

                $('#beds-list').html(tableHtml);
                $('#bed_id').html(bedDropdown);

                // finally show the card
                $('#room-card').fadeIn(200);
            },
            error: function(xhr) {
                console.error(xhr);
                $('#room-card').hide();
            }
        });
    });


    // --------- DROPZONE: multiple files setup -----------
    Dropzone.options.attechmentDropzone = {
        url: '{{ route('admin.ipd-admissions.storeMedia') }}',
        maxFilesize: 20,
        maxFiles: 10,
        addRemoveLinks: true,
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        params: { size: 20 },
        success: function (file, response) {
            if (response && response.name) {
                $('form').append('<input type="hidden" name="attechment[]" value="' + response.name + '">');
                file._serverName = response.name;
            }
        },
        removedfile: function (file) {
            file.previewElement.remove();
            if (file._serverName) {
                $('form').find('input[name="attechment[]"][value="' + file._serverName + '"]').remove();
            }
            this.options.maxFiles = this.options.maxFiles + 1;
        },
        init: function () {
            @if(isset($ipdAdmission) && $ipdAdmission->attechment)
                var files = {!! json_encode($ipdAdmission->attechment) !!};
                if (files) {
                    if (!Array.isArray(files)) files = [files];
                    for (var i=0;i<files.length;i++) {
                        var f = files[i];
                        this.options.addedfile.call(this, f);
                        this.options.thumbnail.call(this, f, f.preview ?? f.url);
                        f.previewElement.classList.add('dz-complete');
                        $('form').append('<input type="hidden" name="attechment[]" value="' + f.file_name + '">');
                        this.options.maxFiles = this.options.maxFiles - 1;
                    }
                }
            @endif
        },
        error: function (file, response) {
            var message = $.type(response) === 'string' ? response : (response.errors ? (response.errors.file || response.errors) : response);
            file.previewElement.classList.add('dz-error')
            var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]');
            for (var _i = 0; _i < _ref.length; _i++) {
                _ref[_i].textContent = message;
            }
        }
    };
});
</script>


<script>
    Dropzone.options.attechmentDropzone = {
    url: '{{ route('admin.ipd-admissions.storeMedia') }}',
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
      $('form').find('input[name="attechment"]').remove()
      $('form').append('<input type="hidden" name="attechment" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="attechment"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($ipdAdmission) && $ipdAdmission->attechment)
      var file = {!! json_encode($ipdAdmission->attechment) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="attechment" value="' + file.file_name + '">')
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
