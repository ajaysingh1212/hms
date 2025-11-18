@extends('layouts.admin')

@section('styles')
<style>
.appointment-wrapper {
    background: #f4f8ff;
    padding: 25px;
    border-radius: 15px;
}
.doctor-card {
    display: none;
    background: #ffffff;
    padding: 25px;
    border-radius: 16px;
    border-left: 6px solid #007bff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.doctor-title {
    font-size: 22px;
    font-weight: 700;
    color: #003366;
}
.day-box, .info-slot {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    margin: 4px;
    border-radius: 8px;
    border: 1px solid #b7d7ff;
    background: #eaf4ff;
    color: #0056b3;
    gap: 8px;
}
.info-slot {
    background: #e8f7ff;
    border-color: #007bff;
    color: #007bff;
}
.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #002147;
    margin: 15px 0 5px 0;
}
.slot-box {
    padding: 10px 14px;
    border-radius: 8px;
    margin: 5px;
    display: inline-block;
    cursor: pointer;
    transition: 0.2s;
}
.slot-available {
    background: #d4f8d4;
    color: #0a730a;
    border: 1px solid #0a730a;
}
.slot-booked {
    background: #ffd3d3;
    color: #b10000;
    border: 1px solid #b10000;
    opacity: 0.9;
}
.selected-slot {
    border: 2px solid #000;
}
.form-control { border-radius: 10px; }
.btn-submit {
    background: linear-gradient(90deg, #007bff, #0063d6);
    border: none;
    padding: 12px 25px;
    font-size: 18px;
    border-radius: 10px;
}
.badge-slot {
    display:inline-block;
    margin:2px;
    padding:6px 10px;
    border-radius:8px;
    background:#eef7ff;
    color:#0056b3;
    border:1px solid #cfe9ff;
}
.badge-booked {
    display:inline-block;
    margin:2px;
    padding:6px 10px;
    border-radius:8px;
    background:#ffdfe0;
    color:#b10000;
    border:1px solid #f5c8c8;
}
</style>
@endsection

@section('content')

<div class="appointment-wrapper">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <strong><i class="fa fa-calendar-check"></i> Create Appointment</strong>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('admin.appointments.store') }}" id="appointmentForm">
                @csrf

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Patient Number</label>
                        <input type="text" name="patient_number" id="patient_number" class="form-control" readonly required>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Department</label>
                        <select name="department_id" id="department_id" class="form-control select2" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $id => $v)
                                <option value="{{ $id }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>Select Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="form-control select2" required>
                            <option value="">Select Department First</option>
                        </select>
                    </div>
                </div>

                <!-- Doctor Card -->
                <div class="doctor-card" id="doctorCard">
                    <h5 class="doctor-title alert alert-info" id="d_name"></h5>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Experience:</strong> <span id="d_exp"></span> years</p>
                            <p><strong>Phone:</strong> <span id="d_phone"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Qualifications:</strong> <span id="d_qual"></span></p>
                            <p><strong>Fee:</strong> ₹ <span id="d_fee"></span></p>
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
                        <input type="text" name="patient_name" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_number" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Date</label>
                        <input type="text" name="date" id="date" class="form-control date" required>
                    </div>
                </div>

                <!-- Available Slots -->
                <div id="slotSection" style="display:none;">
                    <p class="section-title">Available Time Slots</p>
                    <div id="slotsContainer"></div>
                </div>

                <!-- Appointment Type / Status -->
                <div class="row mt-4">
                    <div class="col-lg-6 form-group">
                        <label>Appointment Type</label>
                        <select name="appointment_type" class="form-control select2">
                            @foreach(\App\Models\Appointment::APPOINTMENT_TYPE_SELECT as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label>Status</label>
                        <select name="status" class="form-control select2">
                            @foreach(\App\Models\Appointment::STATUS_SELECT as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Description (Optional)</label>
                    <textarea name="description" class="form-control ckeditor"></textarea>
                </div>

                <div class="form-group mt-4">
                    <label>Reason For Visit</label>
                    <textarea name="reason_for_visit" class="form-control ckeditor"></textarea>
                </div>

                <!-- Hidden JSON -->
                <input type="hidden" id="available_days_json" name="available_days">
                <input type="hidden" id="doctor_slots_json" name="doctor_slots">
                <input type="hidden" id="slot_id" name="available_slots_id">

                <div class="text-center mt-4">
                    <button class="btn btn-submit text-white">Save Appointment</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
document.addEventListener("DOMContentLoaded", function () {
    // auto patient number
    document.getElementById("patient_number").value =
        Math.floor(10000000 + Math.random() * 90000000);
});
</script>

<script>
$(document).ready(function () {

    function updateJSON(selector, items) {
        $(selector).val(JSON.stringify(items));
    }

    // Load doctors for department
    $('#department_id').change(function () {
        let id = $(this).val();
        $('#doctor_id').html('<option>Loading...</option>');

        $.get("{{ route('admin.getDoctorsByDepartment') }}", { department_id: id }, function (res) {
            $('#doctor_id').html('<option value="">Select Doctor</option>');
            $.each(res, function (id, name) {
                $('#doctor_id').append(`<option value="${id}">${name}</option>`);
            });
            $("#doctorCard").hide();
            $("#d_days").html('');
            $("#d_slots").html('');
            updateJSON('#available_days_json', []);
            updateJSON('#doctor_slots_json', []);
        });
    });

    // When doctor selected -> fetch details (includes booked dates per slot)
    $('#doctor_id').change(function () {
        let id = $(this).val();
        if (!id) { $("#doctorCard").hide(); return; }

        $.get("{{ route('admin.getDoctorDetails') }}", { doctor_id: id }, function (doc) {

            if (doc.error) { alert(doc.error); return; }

            $("#doctorCard").show();
            $("#d_name").text(doc.doctor_name);
            $("#d_exp").text(doc.experience);
            $("#d_phone").text(doc.phone);
            $("#d_qual").text(doc.qualifications);
            $("#d_fee").text(doc.doctor_fee);

            // Render available days
            const week = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
            const selectedDays = Array.isArray(doc.available_days) ? doc.available_days : [];
            $("#d_days").html('');
            week.forEach(day => {
                const checked = selectedDays.includes(day) ? 'checked' : '';
                $("#d_days").append(`<label class="day-box"><input type="checkbox" class="dayCheck" value="${day}" ${checked}> ${day.toUpperCase()}</label>`);
            });
            updateJSON('#available_days_json', selectedDays);

            // day checkbox change
            $(document).off('change', '.dayCheck').on('change', '.dayCheck', function(){
                let arr = [];
                $('.dayCheck:checked').each(function(){ arr.push($(this).val()); });
                updateJSON('#available_days_json', arr);
            });

            // Render slots with booked info
            $("#d_slots").html('');
            const slotList = Array.isArray(doc.slots) ? doc.slots : [];

            // doc.slots each object: { id, select_time, booked_dates: [...], booked_today: bool }
            slotList.forEach(s => {
                const bookedDates = Array.isArray(s.booked_dates) ? s.booked_dates : [];
                const bookedToday = !!s.booked_today;

                // format booked dates to readable (e.g. 2025-11-18 -> 18 Nov 2025)
                const readable = bookedDates.map(d => {
                    // convert YYYY-MM-DD to readable - do in JS
                    try {
                        const dt = new Date(d + 'T00:00:00');
                        const opts = { day:'2-digit', month:'short', year:'numeric' };
                        return dt.toLocaleDateString(undefined, opts);
                    } catch(e) {
                        return d;
                    }
                });

                if (bookedToday) {
                    // booked today -> disabled and red
                    $("#d_slots").append(`
                        <label class="info-slot" style="background:#ffd3d3; border-color:#b10000; color:#b10000; opacity:0.9;">
                            <input type="checkbox" disabled>
                            ${s.select_time} <strong>(Booked Today)</strong>
                            ${readable.length ? `<div style="font-size:11px; margin-left:6px;">Previously: ${readable.join(', ')}</div>` : ''}
                        </label>
                    `);
                } else if (readable.length) {
                    // booked in past dates but not today
                    $("#d_slots").append(`
                        <label class="info-slot" style="background:#fff6e6; border-color:#ffc107; color:#8a6d00;">
                            <input type="checkbox" class="slotCheck" value="${s.select_time}">
                            ${s.select_time}
                            <div style="font-size:11px; margin-left:6px;">Booked on: ${readable.join(', ')}</div>
                        </label>
                    `);
                } else {
                    // free slot
                    $("#d_slots").append(`
                        <label class="info-slot">
                            <input type="checkbox" class="slotCheck" value="${s.select_time}">
                            ${s.select_time}
                        </label>
                    `);
                }
            });

            // Set initial selectedSlots as those non-booked that are CHECKED? (none by default)
            updateJSON('#doctor_slots_json', []);

            // slot checkbox change
            $(document).off('change', '.slotCheck').on('change', '.slotCheck', function(){
                let arr = [];
                $('.slotCheck:checked').each(function(){ arr.push($(this).val()); });
                updateJSON('#doctor_slots_json', arr);
            });

        }).fail(function() {
            alert('Failed to fetch doctor details.');
        });
    });

    // Load available slots for selected date (shows which are booked/available for that date)
    $('#date').change(function () {
        let doctor_id = $('#doctor_id').val();
        let date = $(this).val();
        if (!doctor_id) { alert('Please select doctor first'); return; }
        if (!date) { return; }

        $.get("{{ route('admin.getAvailableSlots') }}", { doctor_id, date }, function (res) {

            $("#slotSection").show();
            $("#slotsContainer").html('');

            // res.available and res.booked are arrays of slot objects (id, select_time)
            (res.available || []).forEach(s => {
                $("#slotsContainer").append(`<span class="slot-box slot-available" data-id="${s.id}">${s.select_time}</span>`);
            });

            (res.booked || []).forEach(s => {
                $("#slotsContainer").append(`<span class="slot-box slot-booked">${s.select_time}</span>`);
            });

        }).fail(function() {
            $("#slotsContainer").html('<div class="text-danger">Failed to load slots</div>');
        });
    });

    // pick a slot for appointment booking (from date's available list)
    $(document).on('click', '.slot-available', function () {
        $('.slot-available').removeClass('selected-slot');
        $(this).addClass('selected-slot');
        $('#slot_id').val($(this).data('id'));
    });

    // ensure JSON hidden inputs updated before submit
    $('#appointmentForm').on('submit', function () {
        // days already updated on change
        let slots = [];
        $('.slotCheck:checked').each(function(){ slots.push($(this).val()); });
        updateJSON('#doctor_slots_json', slots);
    });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // init CKEditor for both textareas
    document.querySelectorAll('.ckeditor').forEach((el) => {
        ClassicEditor.create(el).catch(error => console.error(error));
    });
});
</script>

@endsection
