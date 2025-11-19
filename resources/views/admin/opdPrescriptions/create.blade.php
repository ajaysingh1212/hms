@extends('layouts.admin')
@section('content')

<style>
/* ---------- CUSTOM PROFESSIONAL DESIGN ---------- */

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
</style>

<div class="card">
    <div class="card-header">
        <strong style="font-size:18px;">📝 Create OPD Prescription</strong>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.opd-prescriptions.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- ---------------- FORM GRID ---------------- --}}
            <div class="form-grid">

                {{-- OPD Select --}}
                <div class="form-group">
                    <label for="opd_id">{{ trans('cruds.opdPrescription.fields.opd') }}</label>
                    <select class="form-control select2 {{ $errors->has('opd') ? 'is-invalid' : '' }}" name="opd_id" id="opd_id">
                        <option value="">-- Select OPD --</option>
                        @foreach($opds as $id => $entry)
                            <option value="{{ $id }}">{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('opd'))
                        <span class="text-danger">{{ $errors->first('opd') }}</span>
                    @endif
                </div>

                {{-- Dosage --}}
                <div class="form-group">
                    <label for="dosage">{{ trans('cruds.opdPrescription.fields.dosage') }}</label>
                    <input class="form-control" type="text" name="dosage" id="dosage">
                </div>

                {{-- Duration --}}
                <div class="form-group">
                    <label for="duration">{{ trans('cruds.opdPrescription.fields.duration') }}</label>
                    <input class="form-control" type="text" name="duration" id="duration">
                </div>

            </div> {{-- END GRID --}}

            {{-- MEDICINES --}}
            <div class="form-group">
                <label for="medicines">{{ trans('cruds.opdPrescription.fields.medicine') }}</label>
                <select class="form-control select2" name="medicines[]" id="medicines" multiple>
                    @foreach($medicines as $id => $medicine)
                        <option value="{{ $id }}">{{ $medicine }}</option>
                    @endforeach
                </select>
            </div>

            {{-- INSTRUCTIONS - FULL WIDTH --}}
            <div class="form-group full-row">
                <label for="instructions">{{ trans('cruds.opdPrescription.fields.instructions') }}</label>
                <textarea class="form-control ckeditor" name="instructions" id="instructions"></textarea>
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
                <label>{{ trans('cruds.opdPrescription.fields.attechment') }}</label>
                <div class="attachment-box">
                    <div class="needsclick dropzone" id="attechment-dropzone"></div>
                </div>
            </div>

            {{-- SUBMIT BUTTON --}}
            <button class="btn btn-danger">Save Prescription</button>

        </form>
    </div>
</div>

@endsection

@section('scripts')
@parent

<script>
/* ------------ AJAX LOAD OPD DETAILS ------------- */
$('#opd_id').change(function() {
    let opd_id = $(this).val();
    if (!opd_id) return;

    $.ajax({
        url: "{{ route('admin.opd-prescriptions.opdDetails') }}",
        type: "GET",
        data: { opd_id: opd_id },
        success: function(res) {

            // Doctor
            $('#doc_name').text(res.doctor?.doctor_name || '--');
            $('#doc_phone').text(res.doctor?.phone || '--');
            $('#doc_exp').text(res.doctor?.experience + " yrs" || '--');
            $('#doc_days').text(res.doctor_available_days?.join(", ") || '--');

            // Patient
            $('#pat_name').text(res.patient?.patient_name || '--');
            $('#pat_mobile').text(res.patient?.mobile_number || '--');
            $('#pat_reason').text(res.patient?.reason_for_visit || '--');
            $('#pat_date').text(res.patient?.date || '--');

            // Visit
            $('#v_date').text(res.opd?.visit_date || '--');
            $('#v_time').text(res.opd?.visit_time || '--');
            $('#v_symptoms').text(res.opd?.symptoms || '--');
            $('#v_diagnosis').text(res.opd?.diagnosis || '--');
        }
    });
});
</script>





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
                xhr.open('POST', '{{ route('admin.opd-prescriptions.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $opdPrescription->id ?? 0 }}');
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
    var uploadedAttechmentMap = {}
Dropzone.options.attechmentDropzone = {
    url: '{{ route('admin.opd-prescriptions.storeMedia') }}',
    maxFilesize: 20, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20
    },
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
          var files =
            {!! json_encode($opdPrescription->attechment) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attechment[]" value="' + file.file_name + '">')
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