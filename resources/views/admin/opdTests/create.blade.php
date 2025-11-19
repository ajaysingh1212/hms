@extends('layouts.admin')
@section('content')

<style>
/* PROFESSIONAL 4-CARD LAYOUT (matches OPD Prescription style) */
.form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
@media (max-width:992px) { .form-grid { grid-template-columns: 1fr; } }
.full-row { width:100%; margin-bottom:12px; }

.info-area { display:flex; gap:15px; flex-wrap:wrap; margin-top:18px; margin-bottom:18px; }
.info-card {
  flex:1 1 calc(25% - 15px);
  min-width:220px;
  background:#fff; padding:14px; border-radius:10px;
  border:1px solid rgba(0,0,0,0.04); box-shadow:0 6px 18px rgba(20,20,40,0.04);
}
@media (max-width:1200px){ .info-card{flex:1 1 calc(50% - 15px);} }
@media (max-width:768px){ .info-card{flex:1 1 100%;} }
.info-card h5{ margin-bottom:10px; color:#223; }
.info-item{ font-size:14px; margin-bottom:8px; }
.attachment-box{ border:2px dashed #d9e8ff; background:#fbfeff; padding:12px; border-radius:10px; }
</style>

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.opdTest.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.opd-tests.store') }}" enctype="multipart/form-data" id="opdTestForm">
            @csrf

            {{-- GRID: opd select + empty slots --}}
            <div class="form-grid">
                <div class="form-group">
                    <label class="required" for="opd_id">{{ trans('cruds.opdTest.fields.opd') }}</label>
                    <select class="form-control select2" name="opd_id" id="opd_id" required>
                        <option value="">{{ trans('global.pleaseSelect') }}</option>
                        @foreach($opds as $id => $entry)
                            <option value="{{ $id }}" {{ old('opd_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="placeholder1">&nbsp;</label>
                    <input type="text" class="form-control" disabled placeholder="Quick Action">
                </div>

                <div class="form-group">
                    <label for="placeholder2">&nbsp;</label>
                    <input type="text" class="form-control" disabled placeholder="Quick Info">
                </div>
            </div>

            {{-- Tests multi-select --}}
            <div class="form-group">
                <label for="tests">{{ trans('cruds.opdTest.fields.test') }}</label>
                <div style="padding-bottom:4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius:0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius:0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2" name="tests[]" id="tests" multiple>
                    @foreach($tests as $id => $test)
                        <option value="{{ $id }}" {{ in_array($id, old('tests', [])) ? 'selected' : '' }}>{{ $test }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Notes --}}
            <div class="form-group full-row">
                <label for="notes">{{ trans('cruds.opdTest.fields.notes') }}</label>
                <textarea class="form-control ckeditor" name="notes" id="notes">{!! old('notes') !!}</textarea>
            </div>

            {{-- INFO CARDS: Doctor | Patient | OPD Visit | Prescription --}}
            <div class="info-area">

                <div class="info-card" id="doctorCard">
                    <h5>👨‍⚕️ Doctor</h5>
                    <div class="info-item"><strong>Name:</strong> <span id="doc_name">--</span></div>
                    <div class="info-item"><strong>Phone:</strong> <span id="doc_phone">--</span></div>
                    <div class="info-item"><strong>Experience:</strong> <span id="doc_exp">--</span></div>
                    <div class="info-item"><strong>Available:</strong> <span id="doc_days">--</span></div>
                </div>

                <div class="info-card" id="patientCard">
                    <h5>🧑 Patient</h5>
                    <div class="info-item"><strong>Name:</strong> <span id="pat_name">--</span></div>
                    <div class="info-item"><strong>Mobile:</strong> <span id="pat_mobile">--</span></div>
                    <div class="info-item"><strong>Reason:</strong> <span id="pat_reason">--</span></div>
                    <div class="info-item"><strong>Date:</strong> <span id="pat_date">--</span></div>
                </div>

                <div class="info-card" id="visitCard">
                    <h5>📄 OPD Visit</h5>
                    <div class="info-item"><strong>Visit Date:</strong> <span id="v_date">--</span></div>
                    <div class="info-item"><strong>Visit Time:</strong> <span id="v_time">--</span></div>
                    <div class="info-item"><strong>Symptoms:</strong> <span id="v_symptoms">--</span></div>
                    <div class="info-item"><strong>Diagnosis:</strong> <span id="v_diagnosis">--</span></div>
                </div>

                <div class="info-card" id="prescriptionCard">
                    <h5>💊 Latest Prescription</h5>
                    <div class="info-item"><strong>Dosage:</strong> <span id="pres_dosage">--</span></div>
                    <div class="info-item"><strong>Duration:</strong> <span id="pres_duration">--</span></div>
                    <div class="info-item"><strong>Instructions:</strong> <span id="pres_instructions">--</span></div>
                    <div class="info-item"><strong>Medicines:</strong>
                        <div id="pres_medicines">--</div>
                    </div>
                </div>

            </div>

            {{-- Submit --}}
            <div class="form-group">
                <button class="btn btn-danger" type="submit">{{ trans('global.save') }}</button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
@parent

<script>
$(function () {
    // ajax setup
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // select all / deselect all
    $('.select-all').click(function(){
        $('#tests option').prop('selected', true);
        $('#tests').trigger('change');
    });
    $('.deselect-all').click(function(){
        $('#tests option').prop('selected', false);
        $('#tests').trigger('change');
    });

    // fetch details on opd select
    $('#opd_id').on('change', function() {
        var opdId = $(this).val();
        if (!opdId) {
            resetCards();
            return;
        }

        $.getJSON("{{ route('admin.opd-tests.opdDetails') }}", { opd_id: opdId })
            .done(function(res) {
                if (res.error) { resetCards(); return; }
                populateCards(res);
            })
            .fail(function() { resetCards(); });
    });

    function resetCards() {
        $('#doc_name, #doc_phone, #doc_exp, #doc_days').text('--');
        $('#pat_name, #pat_mobile, #pat_reason, #pat_date').text('--');
        $('#v_date, #v_time, #v_symptoms, #v_diagnosis').text('--');
        $('#pres_dosage, #pres_duration, #pres_instructions').text('--');
        $('#pres_medicines').html('--');
    }

    function populateCards(res) {
        var opd = res.opd || {};
        var doctor = res.doctor || {};
        var patient = res.patient || {};
        var prescription = res.prescription || null;
        var doctorDays = res.doctor_available_days || [];

        $('#doc_name').text(doctor.doctor_name || '--');
        $('#doc_phone').text(doctor.phone || '--');
        $('#doc_exp').text(doctor.experience ? doctor.experience + ' yrs' : '--');
        $('#doc_days').text(Array.isArray(doctorDays) ? doctorDays.map(capFirst).join(', ') : '--');

        $('#pat_name').text(patient.patient_name || '--');
        $('#pat_mobile').text(patient.mobile_number || '--');
        $('#pat_reason').text(patient.reason_for_visit || '--');
        $('#pat_date').text(patient.date || '--');

        $('#v_date').text(opd.visit_date || '--');
        $('#v_time').text(opd.visit_time || '--');
        $('#v_symptoms').text(opd.symptoms || '--');
        $('#v_diagnosis').text(opd.diagnosis || '--');

        if (prescription) {
            $('#pres_dosage').text(prescription.dosage || '--');
            $('#pres_duration').text(prescription.duration || '--');
            $('#pres_instructions').text(prescription.instructions || '--');

            if (Array.isArray(prescription.medicines) && prescription.medicines.length) {
                var html = '<table class="table table-sm"><thead><tr><th>Name</th><th>Price</th></tr></thead><tbody>';
                prescription.medicines.forEach(function(m){
                    html += '<tr><td>' + escapeHtml(m.name) + '</td><td>' + (m.price !== null ? m.price : '-') + '</td></tr>';
                });
                html += '</tbody></table>';
                $('#pres_medicines').html(html);
            } else {
                $('#pres_medicines').html('No medicines');
            }
        } else {
            $('#pres_dosage').text('--');
            $('#pres_duration').text('--');
            $('#pres_instructions').text('--');
            $('#pres_medicines').html('No prescription found');
        }
    }

    function capFirst(s){ if(!s) return s; return s.charAt(0).toUpperCase()+s.slice(1); }
    function escapeHtml(text) { if (!text) return ''; return $('<div/>').text(text).html(); }

    // optionally: if old selected opd exists on load, trigger change
    var initialOpd = '{{ old('opd_id') }}';
    if (initialOpd) $('#opd_id').trigger('change');

    // CKEditor adapter same as your other pages
    function SimpleUploadAdapter(editor) {
      editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
        return {
          upload: function() {
            return loader.file.then(function (file) {
              return new Promise(function(resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.opd-tests.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $opdTest->id ?? 0 }}');
                xhr.send(data);
              });
            });
          }
        };
      }
    }

    var allEditors = document.querySelectorAll('.ckeditor');
    for (var i = 0; i < allEditors.length; ++i) {
      ClassicEditor.create(allEditors[i], { extraPlugins: [SimpleUploadAdapter] });
    }

});
</script>

@endsection
