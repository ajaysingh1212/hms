@extends('layouts.admin')

@section('content')

<style>
.card-creative{border-radius:12px;box-shadow:0 6px 18px rgba(0,0,0,0.06);overflow:hidden}
.card-creative .card-header{background:linear-gradient(90deg,#6f42c1,#1f8ef1);color:#fff;font-weight:600}
.info-pill{display:inline-block;padding:4px 10px;border-radius:20px;background:rgba(0,0,0,0.06);font-size:12px}
.small-muted{font-size:13px;color:#6c757d}
.meta{font-size:13px;color:#495057}
.upload-card{border-radius:10px;border:2px dashed #e9ecef;background:linear-gradient(180deg,#fff,#f8f9fa);padding:16px}
.room-beds-table td,.room-beds-table th{vertical-align:middle}
</style>

<div class="card card-creative">
    <div class="card-header">
        <i class="fas fa-edit mr-2"></i> Edit IPD Admission
    </div>

    <div class="card-body">

<form method="POST" action="{{ route('admin.ipd-admissions.update',$ipdAdmission->id) }}" enctype="multipart/form-data" id="ipd-form">
    @csrf
    @method('PUT')

    {{-- ============================= TOP ROW ============================= --}}
    <div class="row mb-3">

        {{-- Patient --}}
        <div class="col-lg-4">
            <label class="required">Patient</label>
            <select id="patient_id" name="patient_id" class="form-control select2" required>
                <option value="">Select</option>
                @foreach($patients as $id=>$entry)
                    <option value="{{ $id }}" {{ $ipdAdmission->patient_id==$id?'selected':'' }}>
                        {{ $entry }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Doctor --}}
        <div class="col-lg-4">
            <label>Doctor</label>
            <select id="doctor_id" name="doctor_id" class="form-control select2">
                <option value="">Select</option>
                @foreach($doctors as $id=>$entry)
                    <option value="{{ $id }}" {{ $ipdAdmission->doctor_id==$id?'selected':'' }}>
                        {{ $entry }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- IPD Number --}}
        <div class="col-lg-4">
            <label>IPD Number</label>
            <input type="text" id="ipd_number" name="ipd_number" class="form-control"
                value="{{ $ipdAdmission->ipd_number }}" readonly>
        </div>

    </div>

    {{-- ============================= 3 CARDS ============================= --}}
    <div class="row mb-4">
        {{-- Card 1: Appointment --}}
        <div class="col-lg-4">
            <div id="card-appointment" class="card card-creative">
                <div class="card-header"><i class="fas fa-calendar-check mr-2"></i> Appointment</div>
                <div class="card-body">
                    <h5 id="a_patient"></h5>
                    <div class="small-muted"><i class="fas fa-phone-alt"></i> <span id="a_mobile"></span></div>

                    <div class="mt-2 meta"><strong>Date:</strong> <span id="a_date"></span></div>
                    <div class="meta"><strong>Type:</strong> <span id="a_type"></span></div>
                    <div class="meta"><strong>Status:</strong> <span id="a_status"></span></div>

                    <div class="mt-2">
                        <strong>Reason:</strong>
                        <div id="a_reason"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Doctor --}}
        <div class="col-lg-4">
            <div id="card-doctor" class="card card-creative">
                <div class="card-header"><i class="fas fa-user-md mr-2"></i> Doctor</div>
                <div class="card-body">
                    <h5 id="d_name"></h5>
                    <div class="small-muted" id="d_department"></div>

                    <div class="row mt-2">
                        <div class="col-6"><strong>Fees:</strong> ₹<span id="d_fee"></span></div>
                        <div class="col-6"><strong>Exp:</strong> <span id="d_exp"></span> yrs</div>
                    </div>

                    <div class="mt-2"><strong>Qualif:</strong> <div id="d_qual"></div></div>
                    <div class="mt-2 small-muted"><i class="fas fa-phone"></i> <span id="d_phone"></span></div>

                    <div class="mt-2"><strong>Avail Days:</strong>
                        <span id="d_days" class="info-pill"></span></div>

                    <div class="mt-2 small-muted" id="d_desc"></div>
                </div>
            </div>
        </div>

        {{-- Card 3: Summary --}}
        <div class="col-lg-4">
            <div id="card-summary" class="card card-creative">
                <div class="card-header"><i class="fas fa-info-circle mr-2"></i> Summary</div>
                <div class="card-body">
                    <div class="meta"><strong>Appointment ID:</strong> <span id="s_appid"></span></div>
                    <div class="meta"><strong>Patient No:</strong> <span id="s_pno"></span></div>
                    <div class="meta"><strong>Created At:</strong> <span id="s_created"></span></div>

                    <div class="mt-2"><strong>Doc Slot:</strong>
                        <div id="s_slot"></div></div>

                    <div class="mt-2"><strong>Avail Days:</strong>
                        <span id="s_days"></span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================= FORM FIELDS ============================= --}}
    <div class="row">
        <div class="col-lg-4">
            <label>Admission Date</label>
            <input type="text" name="admission_date" id="admission_date"
                   class="form-control date"
                   value="{{ $ipdAdmission->admission_date }}">
        </div>

        <div class="col-lg-4">
            <label>Admission Time</label>
            <input type="text" name="admission_time" id="admission_time"
                   class="form-control timepicker"
                   value="{{ $ipdAdmission->admission_time }}">
        </div>

        <div class="col-lg-4">
            <label>Room</label>
            <select id="room_id" name="room_id" class="form-control select2">
                @foreach($rooms as $id=>$entry)
                    <option value="{{ $id }}" {{ $ipdAdmission->room_id==$id?'selected':'' }}>
                        {{ $entry }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Next Row --}}
    <div class="row mt-3">
        <div class="col-lg-4">
            <label>Bed</label>
            <select id="bed_id" name="bed_id" class="form-control select2">
                @foreach($beds as $id=>$entry)
                    <option value="{{ $id }}" {{ $ipdAdmission->bed_id==$id?'selected':'' }}>
                        {{ $entry }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-4">
            <label>Condition</label>
            <input type="text" class="form-control" name="condition_on_admission"
                   value="{{ $ipdAdmission->condition_on_admission }}">
        </div>

        <div class="col-lg-4">
            <label>Status</label>
            <select id="status" name="status" class="form-control">
                @foreach(App\Models\IpdAdmission::STATUS_SELECT as $key=>$label)
                <option value="{{ $key }}" {{ $ipdAdmission->status==$key?'selected':'' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ============================= REASON FULL WIDTH ============================= --}}
    <div class="mt-3">
        <label>Reason</label>
        <textarea name="reason" id="reason" class="form-control ckeditor">{!! $ipdAdmission->reason !!}</textarea>
    </div>

    {{-- ============================= ATTACHMENTS ============================= --}}
    <div class="mt-4">
        <label>Attachments</label>
        <div class="upload-card">
            <div class="d-flex align-items-center">
                <div style="font-size:28px;margin-right:12px;"><i class="fas fa-cloud-upload-alt"></i></div>
                <div><h6 class="mb-0">Upload files</h6>
                    <small class="text-muted">Multiple files allowed</small>
                </div>
            </div>

            <div class="needsclick dropzone mt-3" id="attechment-dropzone"></div>
        </div>
    </div>

    {{-- ============================= ROOM CARD ============================= --}}
    <div class="row mt-4">
        <div class="col-lg-12">
<div id="room-card" class="card card-creative" style="display:none;">
    <div class="card-header"><i class="fas fa-door-open mr-2"></i> Room Details</div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-3"><strong>Room No:</strong> <span id="r_no"></span></div>
            <div class="col-lg-3"><strong>Ward:</strong> <span id="r_ward"></span></div>
            <div class="col-lg-3"><strong>Charges/Day:</strong> ₹<span id="r_charges"></span></div>
            <div class="col-lg-3"><strong>Status:</strong> <span id="r_status"></span></div>
        </div>

        <div class="mt-3">
            <h6>Available Beds</h6>
            <table class="table table-sm table-striped room-beds-table">
                <thead>
                    <tr>
                        <th>Bed No</th>
                        <th>Charges</th>
                        <th>Status</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody id="beds-list"></tbody>
            </table>
        </div>
    </div>
</div>
        </div>
    </div>

    {{-- ============================= SUBMIT ============================= --}}
    <div class="text-right mt-4">
        <button class="btn btn-primary"><i class="fas fa-save mr-1"></i> Update</button>
    </div>

</form>

</div>
</div>

@endsection


@section('scripts')
<script>
/* -------------------------------------------------------------------
   CKEDITOR
------------------------------------------------------------------- */
function SimpleUploadAdapter(editor){
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader){
        return {
            upload:function(){
                return loader.file.then(function(file){
                    return new Promise(function(resolve, reject){
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route('admin.ipd-admissions.storeCKEditorImages') }}', true);
                        xhr.setRequestHeader('x-csrf-token', window._token);
                        xhr.setRequestHeader('Accept', 'application/json');
                        xhr.responseType='json';

                        xhr.addEventListener('load',function(){
                            if(xhr.status!==201){ reject("Upload failed"); return; }
                            $('form').append('<input type="hidden" name="ck-media[]" value="'+xhr.response.id+'">');
                            resolve({default:xhr.response.url});
                        });

                        var data=new FormData();
                        data.append('upload',file);
                        data.append('crud_id','{{ $ipdAdmission->id }}');
                        xhr.send(data);
                    });
                });
            }
        };
    };
}
document.querySelectorAll('.ckeditor').forEach(el => {
    ClassicEditor.create(el, {
        extraPlugins:[SimpleUploadAdapter]
    });
});

/* -------------------------------------------------------------------
   PATIENT → LOAD APPOINTMENT / DOCTOR CARDS
------------------------------------------------------------------- */
function loadAppointment(pid){
    if(!pid) return;

    $.ajax({
        url:"{{ route('admin.ipd-admissions.getAppointmentDetails') }}",
        data:{patient_id:pid},
        success:function(res){
            if(!res.appointment) return;

            let a = res.appointment;
            $('#a_patient').text(a.patient_name);
            $('#a_mobile').text(a.mobile_number);
            $('#a_date').text(a.date);
            $('#a_type').text(a.appointment_type);
            $('#a_status').text(a.status);
            $('#a_reason').text(a.reason_for_visit);

            $('#card-appointment').show();

            let d = res.doctor;
            if(d){
                $('#d_name').text(d.doctor_name);
                $('#d_department').text(d.select_department?.name ?? "-");
                $('#d_fee').text(d.doctor_fee);
                $('#d_exp').text(d.experience ?? "-");
                $('#d_qual').text(d.qualifications ?? "-");
                $('#d_phone').text(d.phone ?? "-");
                $('#d_days').text((d.available_days ?? []).join(", "));
                $('#d_desc').text(d.description ?? "-");

                // auto-select in doctor dropdown
                $('#doctor_id').val(d.id).trigger('change');

                $('#card-doctor').show();
            }

            let s = res.summary;
            if(s){
                $('#s_appid').text(s.appointment_id);
                $('#s_pno').text(s.patient_number);
                $('#s_created').text(s.created_at);
                $('#s_slot').text(Array.isArray(s.doctor_slot) ? s.doctor_slot.join(', ') : s.doctor_slot);
                $('#s_days').text(Array.isArray(s.available_days) ? s.available_days.join(', ') : s.available_days);

                $('#card-summary').show();
            }
        }
    });
}

/* Auto-load for edit */
loadAppointment($('#patient_id').val());

$('#patient_id').change(function(){
    loadAppointment($(this).val());
});


/* -------------------------------------------------------------------
   ROOM → LOAD BEDS + ROOM CARD
------------------------------------------------------------------- */
function loadRoom(rid){
    if(!rid){
        $('#room-card').hide();
        return;
    }

    $.ajax({
        url:"{{ route('admin.ipd-admissions.getRoomDetails') }}",
        data:{room_id:rid},
        success:function(res){
            if(!res.room){ $('#room-card').hide(); return; }

            let r = res.room;
            $('#r_no').text(r.room_no);
            $('#r_ward').text(r.ward_type);
            $('#r_charges').text(r.charges_per_day);
            $('#r_status').text(r.status);

            let beds = res.beds;
            let html = "";
            let dropdown = `<option value="">Select</option>`;

            if(beds.length==0){
                html = `<tr><td colspan="4" class="text-center text-muted">No available beds</td></tr>`;
            } else {
                beds.forEach(b=>{
                    html += `
                        <tr>
                            <td>${b.bed_no}</td>
                            <td>₹${b.charges_per_day}</td>
                            <td>${b.status}</td>
                            <td>${b.notes ?? '-'}</td>
                        </tr>
                    `;
                    dropdown += `<option value="${b.id}">${b.bed_no} — ₹${b.charges_per_day}</option>`;
                });
            }

            $('#beds-list').html(html);
            $('#bed_id').html(dropdown);

            $('#room-card').fadeIn(200);
        }
    });
}

// auto-load for edit
loadRoom($('#room_id').val());

$('#room_id').change(function(){
    loadRoom($(this).val());
});


/* -------------------------------------------------------------------
   DROPZONE
------------------------------------------------------------------- */
Dropzone.options.attechmentDropzone = {
    url:"{{ route('admin.ipd-admissions.storeMedia') }}",
    maxFilesize:20,
    maxFiles:10,
    addRemoveLinks:true,
    headers:{'X-CSRF-TOKEN':"{{ csrf_token() }}"},
    params:{size:20},
    success:function(file,response){
        $('form').append('<input type="hidden" name="attechment[]" value="'+response.name+'">');
        file._serverName=response.name;
    },
    removedfile:function(file){
        file.previewElement.remove();
        if(file._serverName){
            $('form').find('input[value="'+file._serverName+'"]').remove();
        }
    },
    init:function(){
        @foreach($ipdAdmission->getMedia('attechment') as $media)
            var file = {
                name: "{{ $media->file_name }}",
                size: {{ $media->size }},
                url: "{{ $media->getUrl() }}"
            };
            this.emit("addedfile", file);
            this.emit("thumbnail", file, file.url);
            file.previewElement.classList.add('dz-complete');
            $('form').append('<input type="hidden" name="attechment[]" value="{{ $media->file_name }}">');
        @endforeach
    }
};

</script>
@endsection
