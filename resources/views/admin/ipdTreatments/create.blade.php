@extends('layouts.admin')
@section('content')

<style>
.card-creative{
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
    border:0;
}
.card-creative .card-header{
    background:linear-gradient(90deg,#1f8ef1,#6f42c1);
    color:#fff;
    font-weight:600;
}
.section-title{
    background:#f1f3f5;
    padding:6px 12px;
    border-left:4px solid #1f8ef1;
    font-weight:600;
    margin-bottom:8px;
}
.info-label{font-weight:600;}
.info-text{color:#495057;}
.big-label{font-size:18px;font-weight:600;}
.pill{
    background:#eef2ff;
    padding:3px 10px;
    border-radius:20px;
    font-size:12px;
    margin-right:4px;
    display:inline-block;
}
.upload-card{
    border:2px dashed #ced4da;
    padding:15px;
    border-radius:12px;
    background:linear-gradient(180deg,#fff,#f8f9fa);
}
</style>

<div class="card card-creative">
    <div class="card-header">
        <i class="fas fa-notes-medical mr-2"></i>
        Create IPD Treatment
    </div>

    <div class="card-body">

<form method="POST" action="{{ route('admin.ipd-treatments.store') }}" enctype="multipart/form-data" id="treatment-form">
@csrf

{{-- ================== SELECT IPD ================== --}}
<div class="row mb-3">
    <div class="col-lg-4">
        <label class="required">Select IPD Admission</label>
        <select class="form-control select2" name="ipd_id" id="ipd_id" required>
            <option value="">Select IPD</option>
            @foreach($ipds as $id=>$entry)
                <option value="{{ $id }}">{{ $entry }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4">
        <label class="required">Treatment Date</label>
        <input type="text" name="date" id="date" class="form-control date" required>
    </div>
</div>

{{-- ================== AUTO DETAILS ================== --}}
<div id="details-section" style="display:none;">

    <h5 class="section-title">Auto Fetched IPD Details</h5>

    <div class="row">

        {{-- IPD DETAILS --}}
        <div class="col-lg-6">
            <div class="card card-creative mb-3">
                <div class="card-header">IPD Admission Details</div>
                <div class="card-body">
                    <div><span class="info-label">IPD Number:</span> <span id="ipd_no"></span></div>
                    <div><span class="info-label">Admit Date:</span> <span id="ipd_date"></span></div>
                    <div><span class="info-label">Admit Time:</span> <span id="ipd_time"></span></div>
                    <div><span class="info-label">Status:</span> <span id="ipd_status"></span></div>
                </div>
            </div>
        </div>

        {{-- PATIENT --}}
        <div class="col-lg-6">
            <div class="card card-creative mb-3">
                <div class="card-header">Patient Details</div>
                <div class="card-body">
                    <div><span class="info-label">Patient:</span> <span id="p_name"></span></div>
                    <div><span class="info-label">Mobile:</span> <span id="p_mobile"></span></div>
                    <div><span class="info-label">Reason:</span> <span id="p_reason"></span></div>
                    <div><span class="info-label">Department:</span> <span id="p_dep"></span></div>
                </div>
            </div>
        </div>

        {{-- DOCTOR --}}
        <div class="col-lg-6">
            <div class="card card-creative mb-3">
                <div class="card-header">Doctor Details</div>
                <div class="card-body">
                    <div class="big-label" id="d_name"></div>
                    <div><strong>Department:</strong> <span id="d_dep"></span></div>
                    <div><strong>Fee:</strong> ₹<span id="d_fee"></span></div>
                    <div><strong>Experience:</strong> <span id="d_exp"></span> yrs</div>
                    <div><strong>Qualifications:</strong> <span id="d_qual"></span></div>
                    <div><strong>Phone:</strong> <span id="d_phone"></span></div>
                    <div class="mt-2"><strong>Available Days:</strong> <span id="d_days"></span></div>
                </div>
            </div>
        </div>

        {{-- ROOM & BEDS --}}
        <div class="col-lg-6">
            <div class="card card-creative mb-3">
                <div class="card-header">Room & Beds</div>
                <div class="card-body">
                    <div><span class="info-label">Room No:</span> <span id="r_no"></span></div>
                    <div><span class="info-label">Ward:</span> <span id="r_ward"></span></div>
                    <div><span class="info-label">Charges:</span> ₹<span id="r_charge"></span></div>

                    <h6 class="mt-3">Bed Details</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Bed</th>
                                <th>Charge</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="bed_list"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ================== TREATMENT ================== --}}
<h5 class="section-title mt-4">Doctor Notes & Treatment</h5>

<div class="row">
    <div class="col-lg-6">
        <label>Doctor Notes</label>
        <textarea name="doctor_notes" class="form-control ckeditor"></textarea>
    </div>
    <div class="col-lg-6">
        <label>Diagnosis</label>
        <textarea name="diagnosis" class="form-control ckeditor"></textarea>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <label>Treatment</label>
        <textarea name="treatment" class="form-control ckeditor"></textarea>
    </div>
</div>

{{-- ================== ATTACHMENT ================== --}}
<div class="mt-4">
    <label>Attachments</label>
    <div class="upload-card">
        <div class="d-flex align-items-center">
            <div style="font-size:30px;margin-right:8px;color:#1f8ef1">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div>
                <strong>Upload Files</strong><br>
                <small class="text-muted">Multiple attachments allowed</small>
            </div>
        </div>

        <div class="needsclick dropzone mt-3" id="attechment-dropzone"></div>
    </div>
</div>

<div class="text-right mt-4">
    <button class="btn btn-primary">
        <i class="fas fa-save mr-1"></i> Save Treatment
    </button>
</div>

</form>

</div>
</div>

@endsection

@section('scripts')
<script>

/* ------------ CKEDITOR UPLOAD ------------- */
function SimpleUploadAdapter(editor){
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader){
        return{
            upload:()=>{
                return loader.file.then(file=>{
                    return new Promise((resolve,reject)=>{
                        var xhr=new XMLHttpRequest();
                        xhr.open('POST',"{{ route('admin.ipd-treatments.storeCKEditorImages') }}");
                        xhr.setRequestHeader('x-csrf-token',window._token);
                        xhr.responseType='json';

                        xhr.onload=function(){
                            if(xhr.status!==201){ reject("Upload failed"); return; }
                            $('form').append('<input type="hidden" name="ck-media[]" value="'+xhr.response.id+'">');
                            resolve({default:xhr.response.url});
                        };

                        var data=new FormData();
                        data.append('upload',file);
                        xhr.send(data);
                    });
                });
            }
        };
    };
}

document.querySelectorAll('.ckeditor').forEach(el=>{
    ClassicEditor.create(el,{ extraPlugins:[SimpleUploadAdapter] });
});

/* ------------ FETCH ALL IPD DETAILS ------------- */

$('#ipd_id').change(function () {

    let ipd = $(this).val();
    if (!ipd) return;

    $.ajax({
        url: "{{ route('admin.ipd-treatments.getIpdDetails') }}",
        data: { ipd_id: ipd },
        success: function (res) {

            $('#details-section').show();

            /* ========== IPD DETAILS ========== */
            let a = res.ipd;
            $('#ipd_no').text(a.ipd_number ?? '-');
            $('#ipd_date').text(a.admission_date ?? '-');
            $('#ipd_time').text(a.admission_time ?? '-');
            $('#ipd_status').text(a.status ?? '-');

            /* ========== PATIENT ========== */
            let p = res.patient;
            $('#p_name').text(p.patient_name ?? '-');
            $('#p_mobile').text(p.mobile_number ?? '-');
            $('#p_reason').text(p.reason_for_visit ?? '-');
            $('#p_dep').text(p.department_name ?? '-');

            /* ========== DOCTOR ========== */
            let d = res.doctor;
            $('#d_name').text(d.doctor_name ?? '-');
            $('#d_dep').text(d.doctor_department ?? '-');
            $('#d_fee').text(d.doctor_fee ?? '-');
            $('#d_exp').text(d.experience ?? '-');
            $('#d_qual').text(d.qualifications ?? '-');
            $('#d_phone').text(d.phone ?? '-');

            let daysHtml = "";
            (d.available_days || []).forEach(day => {
                daysHtml += `<span class="pill">${day}</span>`;
            });
            $('#d_days').html(daysHtml);

            /* ========== ROOM ========== */
            let r = res.room;
            $('#r_no').text(r.room_no ?? '-');
            $('#r_ward').text(r.ward_type ?? '-');
            $('#r_charge').text(r.charges_per_day ?? '-');

            /* ========== BEDS LIST ========== */
            let bedHTML = "";
            res.beds.forEach(b => {
                bedHTML += `
                    <tr>
                        <td>${b.bed_no}</td>
                        <td>₹${b.charges_per_day}</td>
                        <td>${b.status}</td>
                    </tr>
                `;
            });

            $('#bed_list').html(bedHTML);
        }
    });

});


/* ------------ DROPZONE MULTIPLE UPLOAD ------------- */
Dropzone.options.attechmentDropzone={
    url:"{{ route('admin.ipd-treatments.storeMedia') }}",
    maxFilesize:20,
    maxFiles:10,
    addRemoveLinks:true,
    headers:{'X-CSRF-TOKEN':"{{ csrf_token() }}"},
    success:function(file,res){
        $('form').append('<input type="hidden" name="attechment[]" value="'+res.name+'">');
        file._serverName=res.name;
    },
    removedfile:function(file){
        file.previewElement.remove();
        $('form').find('input[value="'+file._serverName+'"]').remove();
    }
};

</script>
@endsection
