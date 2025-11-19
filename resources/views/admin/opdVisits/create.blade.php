@extends('layouts.admin')

@section('styles')
<style>
/* ----------- GLOBAL PROFESSIONAL THEME ----------- */
body {
    background: #f5f8fd;
}

/* Section Heading */
.card-header h4 {
    font-weight: 700;
    color: #164c8c;
}

/* ----------------- Enhanced Cards ----------------- */
.info-card {
    border-radius: 16px;
    padding: 20px;
    background: #ffffff url('/hospital-icons/bg-medical.png') no-repeat bottom right;
    background-size: 140px;
    box-shadow: 0 8px 20px rgba(22, 76, 140, 0.12);
    border: 1px solid #e6efff;
    display: none; /* HIDDEN BY DEFAULT */
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.info-card.show {
    display: block;
    animation: fadeIn 0.4s ease;
}

.info-card:hover {
    box-shadow: 0 12px 28px rgba(22, 76, 140, 0.18);
}

.info-card .title {
    font-size: 1rem;
    font-weight: 700;
    color: #164c8c;
    border-bottom: 1px solid #d7e3ff;
    padding-bottom: 6px;
    margin-bottom: 10px;
}

.info-label {
    font-weight: 600;
    color: #3a4a6b;
}

.small-muted {
    font-size: 0.87rem;
    color: #6b7a99;
}

/* ----------- Grid Layout ------------- */
.card-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    margin-top: 10px;
}

.card-unit {
    flex: 1;
    min-width: 280px;
}

/* Fade animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Dropzone Enhancement */
.dropzone-custom {
    background: #f9fbff;
    border: 2px dashed #d0dcf7;
    border-radius: 12px;
}

.dropzone:hover {
    background: #f1f6ff;
}

/* Inputs */
.form-control, .select2-selection {
    border-radius: 10px !important;
    border: 1px solid #c9d8f3;
    box-shadow: 0 2px 5px rgba(22, 76, 140, 0.06);
}

/* When cards hidden */
.hidden-block {
    display: none;
}
</style>
@endsection



@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">{{ trans('global.create') }} OPD Visit</h4>
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.opd-visits.store') }}" enctype="multipart/form-data" id="opd-visit-form">
            @csrf

            <!-- Top Inputs -->
            <div class="row">
                <div class="col-md-4">
                    <label class="required">Patient</label>
                    <select class="form-control select2" id="patient_id" name="patient_id" required>
                        <option value="">-- Select Patient --</option>
                        @foreach($patients as $id => $entry)
                            <option value="{{ $id }}">{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Doctor</label>
                    <select class="form-control select2" id="doctor_id" name="doctor_id">
                        @foreach($doctors as $id => $entry)
                            <option value="{{ $id }}">{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Visit Date</label>
                    <input type="text" class="form-control date" name="visit_date" id="visit_date">
                </div>
            </div>


            <!-- ------------ DETAILS CARDS (HIDDEN UNTIL PATIENT SELECTED) ---------------- -->
            <div class="card-grid mt-3 hidden-block" id="details-section">

                <!-- Patient Card -->
                <div class="info-card card-unit" id="patient-card">
                    <div class="title">Patient Details</div>
                    <div><span class="info-label">Name:</span> <span id="p_name">-</span></div>
                    <div><span class="info-label">Mobile:</span> <span id="p_mobile">-</span></div>
                    <div><span class="info-label">Patient No:</span> <span id="p_number">-</span></div>
                </div>

                <!-- Appointment -->
                <div class="info-card card-unit" id="appointment-card">
                    <div class="title">Appointment</div>
                    <div><span class="info-label">Date:</span> <span id="a_date">-</span></div>
                    <div><span class="info-label">Type:</span> <span id="a_type">-</span></div>
                    <div><span class="info-label">Reason:</span> <span id="a_reason">-</span></div>
                    <div><span class="info-label">Slots:</span> <span id="a_slots">-</span></div>
                </div>

                <!-- Doctor -->
                <div class="info-card card-unit" id="doctor-card">
                    <div class="title">Doctor</div>
                    <div><span class="info-label">Name:</span><span id="d_name">-</span></div>
                    <div><span class="info-label">Dept:</span> <span id="d_department">-</span></div>
                    <div><span class="info-label">Fee:</span> <span id="d_fee">-</span></div>
                    <div><span class="info-label">Phone:</span> <span id="d_phone">-</span></div>
                    <div><span class="info-label">Qual:</span> <span id="d_qual">-</span></div>
                    <div><span class="info-label">Experience:</span> <span id="d_exp">-</span></div>
                    <div><span class="info-label">Available Days:</span> <span id="d_days">-</span></div>
                </div>
            </div>


            <!-- OTHER INPUTS -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <label>Visit Time</label>
                    <input class="form-control timepicker" type="text" name="visit_time" id="visit_time">
                </div>
                <div class="col-md-4">
                    <label>Visit Type</label>
                    <select class="form-control" name="visit_type">
                        <option value="new">New</option>
                        <option value="follow_up">Follow Up</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="scheduled">Scheduled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <!-- Textareas -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <label>Symptoms</label>
                    <textarea class="form-control ckeditor" name="symptoms"></textarea>
                </div>
                <div class="col-md-12 mt-3">
                    <label>Diagnosis</label>
                    <textarea class="form-control ckeditor" name="diagnosis"></textarea>
                </div>
                <div class="col-md-12 mt-3">
                    <label>Notes</label>
                    <textarea class="form-control ckeditor" name="notes"></textarea>
                </div>
            </div>

            <!-- Attachment -->
            <div class="col-md-12 mt-4">
                <label>Attachment</label>
                <div class="dropzone dropzone-custom" id="attechment-dropzone"></div>
            </div>

            <!-- Submit -->
            <div class="mt-4">
                <button class="btn btn-primary px-4 py-2" style="border-radius:10px;font-weight:600;">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>
@endsection



@section('scripts')
@parent

<script>
$(document).ready(function(){

    /* -------------------------------------------------------
       SHOW CARDS WHEN PATIENT SELECTED
    ---------------------------------------------------------- */
    $('#patient_id').change(function () {
        let appointmentId = $(this).val();

        if (!appointmentId) {
            $('#details-section').addClass('hidden-block');
            return;
        }

        $('#details-section').removeClass('hidden-block');
        $('.info-card').addClass('show');


        // Fetch appointment
        var url = "{{ route('admin.opd-visits.appointmentDetails','ID') }}";
        url = url.replace('ID', appointmentId);

        $.get(url, function(res){

            let a = res.appointment;
            let d = res.doctor;

            // Patient
            $('#p_name').text(a.patient_name || '-');
            $('#p_mobile').text(a.mobile_number || '-');
            $('#p_number').text(a.patient_number || '-');

            // Appointment
            $('#a_date').text(a.date || '-');
            $('#a_type').text(a.appointment_type || '-');
            $('#a_reason').text(a.reason_for_visit || '-');
            $('#a_slots').text(a.doctor_slots?.join(', ') || '-');

            // Doctor
            $('#d_name').text(d.doctor_name || '-');
            $('#d_department').text(d.department || '-');
            $('#d_fee').text(d.doctor_fee || '-');
            $('#d_phone').text(d.phone || '-');
            $('#d_qual').text(d.qualifications || '-');
            $('#d_exp').text(d.experience || '-');
            $('#d_days').text(d.available_days?.join(', ') || '-');

            // auto-select doctor
            $('#doctor_id').val(d.id).trigger('change');

        });

    });


});
// initialize select2, datepicker, timepicker (assuming you have these libs)
    $('.select2').select2({ width: '100%' });

    // simple datepicker fallback
    if ($.fn.datepicker) {
        $('.date').datepicker({
            autoclose: true,
            format: "{{ str_replace('Y', 'yyyy', config('panel.date_format')) }}" // try to align format
        });
    }

    if ($.fn.timepicker) {
        $('.timepicker').timepicker({ showMeridian: false });
    }

    // CKEditor upload adapter (same as your existing code)
    function SimpleUploadAdapter(editor) {
      editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
        return {
          upload: function() {
            return loader.file.then(function (file) {
              return new Promise(function(resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.opd-visits.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';
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
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $opdVisit->id ?? 0 }}');
                xhr.send(data);
              });
            })
          }
        };
      }
    }

    var allEditors = document.querySelectorAll('.ckeditor');
    for (var i = 0; i < allEditors.length; ++i) {
      ClassicEditor.create(allEditors[i], { extraPlugins: [SimpleUploadAdapter] }).catch(function(error){ console.error(error); });
    }
</script>

<script>
    // Dropzone config (uses your earlier config with improved style)
    var uploadedAttechmentMap = {}
    Dropzone.options.attechmentDropzone = {
        url: '{{ route('admin.opd-visits.storeMedia') }}',
        maxFilesize: 20, // MB
        addRemoveLinks: true,
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        params: { size: 20 },
        dictDefaultMessage: 'Drop files here or click to upload (Allowed: images, pdf, docs)',
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
            @if(isset($opdVisit) && $opdVisit->attechment)
                var files = {!! json_encode($opdVisit->attechment) !!}
                for (var i in files) {
                  var file = files[i]
                  this.options.addedfile.call(this, file)
                  file.previewElement.classList.add('dz-complete')
                  $('form').append('<input type="hidden" name="attechment[]" value="' + file.file_name + '">')
                }
            @endif
        },
        error: function (file, response) {
             var message = (typeof response === 'string') ? response : (response.errors?.file ?? 'Upload error');
             file.previewElement.classList.add('dz-error')
             var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
             for (var _i = 0; _i < _ref.length; _i++) {
                 _ref[_i].textContent = message
             }
        }
    }
</script>

@endsection
