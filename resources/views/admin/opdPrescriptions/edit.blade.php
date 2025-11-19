@extends('layouts.admin')
@section('content')

<style>
/* ---------- CUSTOM PROFESSIONAL DESIGN (same as create) ---------- */

/* 3 Column Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
@media (max-width: 992px) {
    .form-grid { grid-template-columns: repeat(1, 1fr); }
}

/* Full width textarea */
.full-row { width: 100%; }

/* Info Cards */
.info-area { margin-bottom: 20px; margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap; }

.info-card {
    flex: 1 1 calc(33.33% - 15px);
    background: #fff;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 6px 15px rgba(0,0,0,0.06);
    min-width: 260px;
}
.info-card h5 {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #364f6b;
    border-bottom: 1px solid #f1f1f1;
    padding-bottom: 6px;
}
.info-card .item { font-size: 14px; margin-bottom: 6px; }
.info-card .label { color: #444; font-weight: 600; }

/* Attachments design */
.attachment-box {
    border: 2px dashed #c8d6e5;
    background: #f8fbff;
    padding: 15px;
    border-radius: 10px;
}

/* Small helper text for existing files */
.existing-files { margin-top:8px; font-size:13px; color:#555; }
</style>

<div class="card">
    <div class="card-header">
        <strong style="font-size:18px;">✏️ Edit OPD Prescription</strong>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.opd-prescriptions.update', $opdPrescription->id) }}" enctype="multipart/form-data" id="opdPrescriptionEditForm">
            @method('PUT')
            @csrf

            {{-- ---------------- FORM GRID ---------------- --}}
            <div class="form-grid">

                {{-- OPD Select --}}
                <div class="form-group">
                    <label for="opd_id">{{ trans('cruds.opdPrescription.fields.opd') }}</label>
                    <select class="form-control select2 {{ $errors->has('opd') ? 'is-invalid' : '' }}" name="opd_id" id="opd_id">
                        <option value="">{{ trans('global.pleaseSelect') }}</option>
                        @foreach($opds as $id => $entry)
                            <option value="{{ $id }}" {{ old('opd_id', $opdPrescription->opd_id) == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('opd'))
                        <div class="invalid-feedback">
                            {{ $errors->first('opd') }}
                        </div>
                    @endif
                </div>

                {{-- Dosage --}}
                <div class="form-group">
                    <label for="dosage">{{ trans('cruds.opdPrescription.fields.dosage') }}</label>
                    <input class="form-control {{ $errors->has('dosage') ? 'is-invalid' : '' }}" type="text" name="dosage" id="dosage" value="{{ old('dosage', $opdPrescription->dosage) }}">
                    @if($errors->has('dosage'))
                        <div class="invalid-feedback">
                            {{ $errors->first('dosage') }}
                        </div>
                    @endif
                </div>

                {{-- Duration --}}
                <div class="form-group">
                    <label for="duration">{{ trans('cruds.opdPrescription.fields.duration') }}</label>
                    <input class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}" type="text" name="duration" id="duration" value="{{ old('duration', $opdPrescription->duration) }}">
                    @if($errors->has('duration'))
                        <div class="invalid-feedback">
                            {{ $errors->first('duration') }}
                        </div>
                    @endif
                </div>

            </div> {{-- END GRID --}}

            {{-- MEDICINES --}}
            <div class="form-group">
                <label for="medicines">{{ trans('cruds.opdPrescription.fields.medicine') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('medicines') ? 'is-invalid' : '' }}" name="medicines[]" id="medicines" multiple>
                    @foreach($medicines as $id => $medicine)
                        <option value="{{ $id }}" {{ (in_array($id, old('medicines', $opdPrescription->medicines->pluck('id')->toArray())) ? 'selected' : '') }}>{{ $medicine }}</option>
                    @endforeach
                </select>
                @if($errors->has('medicines'))
                    <div class="invalid-feedback">
                        {{ $errors->first('medicines') }}
                    </div>
                @endif
            </div>

            {{-- INSTRUCTIONS - FULL WIDTH --}}
            <div class="form-group full-row">
                <label for="instructions">{{ trans('cruds.opdPrescription.fields.instructions') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('instructions') ? 'is-invalid' : '' }}" name="instructions" id="instructions">{!! old('instructions', $opdPrescription->instructions) !!}</textarea>
                @if($errors->has('instructions'))
                    <div class="invalid-feedback">
                        {{ $errors->first('instructions') }}
                    </div>
                @endif
            </div>

            {{-- ------------------- INFO CARDS -------------------- --}}
            <div class="info-area">

                {{-- Doctor Card --}}
                <div class="info-card" id="doctorCard">
                    <h5>👨‍⚕️ Doctor Details</h5>
                    <div class="item"><span class="label">Name:</span> <span id="doc_name">--</span></div>
                    <div class="item"><span class="label">Phone:</span> <span id="doc_phone">--</span></div>
                    <div class="item"><span class="label">Experience:</span> <span id="doc_exp">--</span></div>
                    <div class="item"><span class="label">Available Days:</span> <span id="doc_days">--</span></div>
                </div>

                {{-- Patient Card --}}
                <div class="info-card" id="patientCard">
                    <h5>🧑‍💼 Patient Details</h5>
                    <div class="item"><span class="label">Name:</span> <span id="pat_name">--</span></div>
                    <div class="item"><span class="label">Mobile:</span> <span id="pat_mobile">--</span></div>
                    <div class="item"><span class="label">Reason:</span> <span id="pat_reason">--</span></div>
                    <div class="item"><span class="label">Date:</span> <span id="pat_date">--</span></div>
                </div>

                {{-- OPD Visit Card --}}
                <div class="info-card" id="visitCard">
                    <h5>📄 OPD Visit Details</h5>
                    <div class="item"><span class="label">Visit Date:</span> <span id="v_date">--</span></div>
                    <div class="item"><span class="label">Visit Time:</span> <span id="v_time">--</span></div>
                    <div class="item"><span class="label">Symptoms:</span> <span id="v_symptoms">--</span></div>
                    <div class="item"><span class="label">Diagnosis:</span> <span id="v_diagnosis">--</span></div>
                </div>

            </div>

            {{-- ------------------- ATTACHMENT -------------------- --}}
            <div class="form-group">
                <label for="attechment">{{ trans('cruds.opdPrescription.fields.attechment') }}</label>
                <div class="attachment-box">
                    <div class="needsclick dropzone {{ $errors->has('attechment') ? 'is-invalid' : '' }}" id="attechment-dropzone"></div>

                    {{-- show list of existing files (fallback) --}}
                    @if($opdPrescription->attechment && $opdPrescription->attechment->count() > 0)
                        <div class="existing-files">
                            Existing files:
                            <ul>
                                @foreach($opdPrescription->attechment as $file)
                                    <li><a href="{{ $file->getUrl() }}" target="_blank">{{ $file->file_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                @if($errors->has('attechment'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attechment') }}
                    </div>
                @endif
            </div>

            {{-- SUBMIT BUTTON --}}
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
    // set up select-all/deselect-all for medicines (same as create)
    $('.select-all').click(function(){
        let $select = $(this).closest('.form-group').find('select')[0];
        for (let i=0; i<$select.options.length; i++) {
            $select.options[i].selected = true;
        }
        $($select).trigger('change');
    });
    $('.deselect-all').click(function(){
        let $select = $(this).closest('.form-group').find('select')[0];
        for (let i=0; i<$select.options.length; i++) {
            $select.options[i].selected = false;
        }
        $($select).trigger('change');
    });

    // CSRF header for AJAX (if needed)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Fetch OPD details via AJAX
    function fetchOpdDetails(opdId) {
        if (!opdId) {
            resetCards();
            return;
        }

        $.getJSON("{{ route('admin.opd-prescriptions.opdDetails') }}", { opd_id: opdId })
            .done(function(res) {
                if (res.error) {
                    resetCards();
                    return;
                }
                populateCards(res);
            })
            .fail(function() {
                resetCards();
            });
    }

    function resetCards() {
        $('#doc_name').text('--'); $('#doc_phone').text('--'); $('#doc_exp').text('--'); $('#doc_days').text('--');
        $('#pat_name').text('--'); $('#pat_mobile').text('--'); $('#pat_reason').text('--'); $('#pat_date').text('--');
        $('#v_date').text('--'); $('#v_time').text('--'); $('#v_symptoms').text('--'); $('#v_diagnosis').text('--');
    }

    function populateCards(res) {
        var opd = res.opd || {};
        var doctor = res.doctor || {};
        var doctorDays = res.doctor_available_days || [];
        var patient = res.patient || {};

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
    }

    function capFirst(s) { if (!s) return s; return s.charAt(0).toUpperCase() + s.slice(1); }

    // On change of OPD select
    $('#opd_id').on('change', function() {
        var opdId = $(this).val();
        fetchOpdDetails(opdId);
    });

    // On page load, fetch details for selected OPD (if any)
    var initialOpd = '{{ old('opd_id', $opdPrescription->opd_id) }}';
    if (initialOpd) {
        fetchOpdDetails(initialOpd);
    }

    // ---------------- CKEditor SimpleUploadAdapter (same as create) ----------------
    function setupCKEditorUploads() {
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                return {
                    upload: function() {
                        return loader.file.then(function(file) {
                            return new Promise(function(resolve, reject) {
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', '{{ route('admin.opd-prescriptions.storeCKEditorImages') }}', true);
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
                                data.append('crud_id', '{{ $opdPrescription->id ?? 0 }}');
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
    }
    setupCKEditorUploads();

    // ---------------- Dropzone for attachments (edit mode: show existing files) ----------------
    var uploadedAttechmentMap = {}
    Dropzone.options.attechmentDropzone = {
        url: '{{ route('admin.opd-prescriptions.storeMedia') }}',
        maxFilesize: 20, // MB
        addRemoveLinks: true,
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        params: { size: 20 },
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
                var files = {!! json_encode($opdPrescription->attechment) !!}
                for (var i in files) {
                    var file = files[i]
                    this.options.addedfile.call(this, file)
                    // create thumbnail if image
                    if (file.mime_type && file.mime_type.startsWith('image/')) {
                        this.options.thumbnail.call(this, file, file.url)
                    }
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="attechment[]" value="' + file.file_name + '">')
                }
            @endif
        },
        error: function (file, response) {
            var message = typeof response === 'string' ? response : (response.errors ? response.errors.file : 'Upload error')
            file.previewElement.classList.add('dz-error')
            var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            for (var _i = 0; _i < _ref.length; _i++) {
                _ref[_i].textContent = message
            }
        }
    };

});
</script>

@endsection
