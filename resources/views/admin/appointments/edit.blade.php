@extends('layouts.admin')

@section('styles')
<style>
.appointment-wrapper {background:#f4f8ff;padding:25px;border-radius:15px;}
.doctor-card {display:block;background:#fff;padding:25px;border-radius:16px;border-left:6px solid #007bff;box-shadow:0 4px 20px rgba(0,0,0,.08);margin-bottom:20px;}
.doctor-title {font-size:22px;font-weight:700;color:#003366;}
.day-box,.info-slot {display:inline-flex;align-items:center;padding:6px 10px;margin:4px;border-radius:8px;border:1px solid #b7d7ff;background:#eaf4ff;color:#0056b3;gap:8px;}
.info-slot {background:#e8f7ff;border-color:#007bff;color:#007bff;}
.section-title {font-size:18px;font-weight:600;color:#002147;margin:15px 0 5px 0;}
.slot-box {padding:10px 14px;border-radius:8px;margin:5px;display:inline-block;cursor:pointer;transition:.2s;}
.slot-available {background:#d4f8d4;color:#0a730a;border:1px solid #0a730a;}
.slot-booked {background:#ffd3d3;color:#b10000;border:1px solid #b10000;opacity:.9;}
.selected-slot {border:2px solid #000;}
.form-control{border-radius:10px;}
.btn-submit{background:linear-gradient(90deg,#007bff,#0063d6);border:none;padding:12px 25px;font-size:18px;border-radius:10px;}
.small-note{font-size:11px;margin-left:6px;color:#7a5200;}
</style>
@endsection


@section('content')

<div class="appointment-wrapper">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <strong><i class="fa fa-calendar-check"></i> Edit Appointment</strong>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.appointments.update', $appointment->id) }}" id="appointmentForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <label>Patient Number</label>
                        <input type="text" class="form-control" value="{{ $appointment->patient_number }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Department</label>
                        <select id="department_id" name="department_id" class="form-control select2" required>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}" {{ $appointment->department_id == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Select Doctor</label>
                        <select id="doctor_id" name="doctor_id" class="form-control select2" required></select>
                    </div>
                </div>


                <!-- Doctor Card -->
                <div class="doctor-card" id="doctorCard">
                    <h5 id="d_name" class="doctor-title alert alert-info">Loading...</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <p><b>Experience:</b> <span id="d_exp"></span> years</p>
                            <p><b>Phone:</b> <span id="d_phone"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Qualifications:</b> <span id="d_qual"></span></p>
                            <p><b>Fee:</b> ₹ <span id="d_fee"></span></p>
                        </div>
                    </div>

                    <p class="section-title">Available Days</p>
                    <div id="d_days"></div>

                    <p class="section-title">Doctor Slots</p>
                    <div id="d_slots"></div>
                </div>



                <div class="row mt-4">
                    <div class="col-md-4">
                        <label>Patient Name</label>
                        <input type="text" name="patient_name" class="form-control" value="{{ $appointment->patient_name }}" required>
                    </div>

                    <div class="col-md-4">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control" value="{{ $appointment->mobile_number }}" required>
                    </div>

                    <div class="col-md-4">
                        <label>Date</label>
                        <input type="text" id="date" name="date"
                               value="{{ $appointment->date }}"
                               class="form-control date" readonly required>
                    </div>
                </div>


                <div id="slotSection" class="mt-3">
                    <p class="section-title">Available Time Slots</p>
                    <div id="slotsContainer"></div>
                </div>


                <div class="row mt-4">
                    <div class="col-md-6">
                        <label>Appointment Type</label>
                        <select name="appointment_type" class="form-control select2">
                            @foreach(\App\Models\Appointment::APPOINTMENT_TYPE_SELECT as $id => $label)
                                <option value="{{ $id }}" {{ $appointment->appointment_type == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Status</label>
                        <select name="status" class="form-control select2">
                            @foreach(\App\Models\Appointment::STATUS_SELECT as $id => $label)
                                <option value="{{ $id }}" {{ $appointment->status == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <label class="mt-4">Description</label>
                <textarea name="description" class="form-control ckeditor">{!! $appointment->description !!}</textarea>

                <label class="mt-3">Reason For Visit</label>
                <textarea name="reason_for_visit" class="form-control ckeditor">{!! $appointment->reason_for_visit !!}</textarea>


                <input type="hidden" id="available_days_json" name="available_days">
                <input type="hidden" id="doctor_slots_json" name="doctor_slots" value="{{ json_encode($appointment->doctor_slots) }}">
                <input type="hidden" id="slot_id" name="available_slots_id" value="{{ $appointment->available_slots_id }}">


                <div class="text-center mt-4">
                    <button class="btn btn-submit text-white">Update Appointment</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
$(document).ready(function(){

    let selectedDoctor = "{{ $appointment->doctor_id }}";
    let selectedSlotId = "{{ $appointment->available_slots_id }}";

    // Load doctors for department
    function loadDoctors(dept){
        $.get("{{ route('admin.getDoctorsByDepartment') }}",{department_id:dept},function(res){

            $('#doctor_id').html('<option value="">Select Doctor</option>');

            $.each(res,function(id,name){
                $('#doctor_id').append(
                    `<option value="${id}" ${id==selectedDoctor?'selected':''}>${name}</option>`
                );
            });

            loadDoctorDetails(selectedDoctor);
        });
    }

    loadDoctors($("#department_id").val());

    $("#department_id").change(function(){
        loadDoctors($(this).val());
    });


    // Load doctor details
    function loadDoctorDetails(id){

        $.get("{{ route('admin.getDoctorDetails') }}",{doctor_id:id},function(doc){

            $("#d_name").text(doc.doctor_name);
            $("#d_exp").text(doc.experience);
            $("#d_phone").text(doc.phone);
            $("#d_qual").text(doc.qualifications);
            $("#d_fee").text(doc.doctor_fee);

            // Days
            $("#d_days").html('');
            doc.available_days.forEach(d=>{
                $("#d_days").append(`
                    <label class="day-box">
                        <input type="checkbox" checked disabled>
                        ${d.toUpperCase()}
                    </label>
                `);
            });

            // Doctor Slot detail card
            $("#d_slots").html('');
            doc.slots.forEach(s=>{

                let isBooked = s.booked_today;

                if(isBooked){
                    $("#d_slots").append(`
                        <label class="info-slot" style="background:#ffd3d3;border-color:#b10000;color:#b10000;">
                            <input type="checkbox" disabled>
                            ${s.select_time} <b>(Booked Today)</b>
                        </label>
                    `);
                }
                else{
                    $("#d_slots").append(`
                        <label class="info-slot">
                            <input type="checkbox" disabled>
                            ${s.select_time}
                        </label>
                    `);
                }
            });


            // AUTO FILL DATE BASED ON DOCTOR DETAILS
            $("#date").val(doc.selected_appointment_date);

            loadAvailableSlots(id, doc.selected_appointment_date);

        });

    }

    $("#doctor_id").change(function(){
        selectedDoctor = $(this).val();
        loadDoctorDetails(selectedDoctor);
    });


    // Load available slots
    function loadAvailableSlots(doctor_id,date){

        $.get("{{ route('admin.getAvailableSlots') }}",{doctor_id,date},function(res){

            $("#slotsContainer").html('');

            res.available.forEach(s=>{

                let active = (s.id == selectedSlotId) ? "selected-slot" : "";

                $("#slotsContainer").append(`
                    <span class="slot-box slot-available ${active}" data-id="${s.id}">
                        ${s.select_time}
                    </span>
                `);
            });

            res.booked.forEach(s=>{
                $("#slotsContainer").append(`
                    <span class="slot-box slot-booked">${s.select_time}</span>
                `);
            });

        });
    }

    $(document).on('click','.slot-available',function(){
        $(".slot-available").removeClass("selected-slot");
        $(this).addClass("selected-slot");
        $("#slot_id").val($(this).data("id"));
    });

});
</script>


<script>
// CKEditor initialize
document.addEventListener("DOMContentLoaded",function(){
    document.querySelectorAll(".ckeditor").forEach(el=>{
        ClassicEditor.create(el).catch(err=>console.error(err));
    });
});
</script>

@endsection
