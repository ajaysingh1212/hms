@extends('layouts.admin')
@section('content')

<style>
/* GLOBAL STYLING */
body { background:#f4f7fb; }
label { font-weight:600; }

/* GLASS CARD */
.glass-card {
    background:rgba(255,255,255,0.75);
    border-radius:18px;
    box-shadow:0 8px 30px rgba(0,0,0,0.08);
    backdrop-filter:blur(8px);
    transition:0.3s;
}
.glass-card:hover {
    transform:translateY(-3px);
    box-shadow:0 10px 35px rgba(0,0,0,0.12);
}

/* SECTION HEADER */
.section-title {
    font-size:18px;
    font-weight:700;
    padding:10px 16px;
    border-left:5px solid #007bff;
    margin-bottom:12px;
}

/* SMALL BADGE */
.info-badge {
    background:#eaf2ff;
    color:#0f5cc4;
    padding:5px 10px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

/* TABLE STYLE */
.table td { vertical-align:middle; }
.table input, .table select {
    height:42px !important;
    border-radius:8px;
}

/* DROPZONE */
.dropzone {
    border:2px dashed #0f62da !important;
    background:#f0f7ff !important;
    border-radius:14px !important;
}
.dropzone .dz-message {
    font-weight:600;
    font-size:17px !important;
    color:#0f62da !important;
}

/* INPUT FOCUS */
.form-control:focus, 
select:focus {
    box-shadow:0 0 0 0.2rem rgba(0,123,255,.25);
}

/* CARD HEADER THEMES */
.card-head-blue { background:#007bff; color:white; }
.card-head-green { background:#28a745; color:white; }
.card-head-orange { background:#ffb100; color:black; }

/* Animations */
.fade-in { animation:fadeIn 0.4s ease-in-out; }
@keyframes fadeIn {
    from { opacity:0; transform:translateY(8px); }
    to { opacity:1; transform:translateY(0); }
}
</style>


<div class="glass-card p-3 mb-4">
    <h3 class="mb-0" style="font-weight:700;">➕ Create IPD Billing</h3>
</div>

<div class="glass-card p-4 fade-in">

    <form method="POST" action="{{ route('admin.ipd-billings.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- SELECT IPD -->
        <div class="section-title">Select IPD</div>
        <div class="row mb-3">
            <div class="col-md-4">
                <label>IPD</label>
                <select class="form-control select2" name="ipd_id" id="ipd_id">
                    @foreach($ipds as $id => $entry)
                        <option value="{{ $id }}">{{ $entry }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-8 d-flex align-items-center text-muted">
                Auto calculates all charges + shows previous payments.
            </div>
        </div>


        <!-- PREVIOUS PAYMENT SUMMARY -->
        <div id="previous-payment-box" class="alert alert-info fade-in" style="display:none; border-radius:12px;">
            <h5><b>Previous Payment Summary</b></h5>
            <div class="mt-2">
                <p>Total Billed Earlier: <b>₹<span id="prev_total_billed"></span></b></p>
                <p>Total Paid Earlier: <b>₹<span id="prev_total_paid"></span></b></p>
                <p>Total Due: <b>₹<span id="prev_due" style="color:#d9534f;"></span></b></p>
                <span id="prev_message" class="badge bg-warning text-dark p-2"></span>
            </div>
        </div>


        <!-- AUTO DETAILS -->
        <div id="ipd-cards" style="display:none;" class="row fade-in">

            <!-- Patient -->
            <div class="col-md-4 mb-3">
                <div class="glass-card p-0">
                    <div class="p-2 card-head-blue">👤 Patient Details</div>
                    <div class="p-3">
                        <p><span class="info-badge">Name</span> <strong id="p_name"></strong></p>
                        <p><span class="info-badge">Mobile</span> <strong id="p_mobile"></strong></p>
                        <p class="small text-muted"><strong>Reason: </strong><span id="p_reason"></span></p>
                        <p class="small text-muted"><strong>Admission Date: </strong><span id="p_admission_date"></span></p>
                        <p class="small text-muted"><strong>Days: </strong><span id="p_days"></span></p>
                    </div>
                </div>
            </div>

            <!-- Doctor -->
            <div class="col-md-4 mb-3">
                <div class="glass-card p-0">
                    <div class="p-2 card-head-green">🩺 Doctor Details</div>
                    <div class="p-3">
                        <p><span class="info-badge">Name</span> <strong id="d_name"></strong></p>
                        <p class="small text-muted"><strong>Department: </strong><span id="d_department"></span></p>
                        <p class="small text-muted"><strong>Fee/day: ₹</strong><span id="d_fee"></span></p>
                        <p class="small text-muted"><strong>Total Doctor Charge: ₹</strong><span id="d_charge"></span></p>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="col-md-4 mb-3">
                <div class="glass-card p-0">
                    <div class="p-2 card-head-orange">💰 Summary</div>
                    <div class="p-3">
                        <p class="small text-muted"><b>Room:</b> ₹<span id="room_charge"></span></p>
                        <p class="small text-muted"><b>Medicine:</b> ₹<span id="medicine_charge"></span></p>
                        <p class="small text-muted"><b>Test:</b> ₹<span id="test_charge"></span></p>
                        <hr>
                        <p class="small text-muted"><b>Subtotal:</b> ₹<span id="subtotal_display"></span></p>
                    </div>
                </div>
            </div>

        </div>






        <!-- CHARGES ENTRY -->
        <div class="section-title mt-4">Charges Entry</div>

        <div class="table-responsive">
            <table class="table table-bordered bg-white">
                <tbody>

                    <!-- MAIN CHARGES -->
                    <tr><td>Doctor Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="doctor_charge" name="doctor_charge"></td>
                    </tr>

                    <tr><td>Room Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="room_charge_input" name="room_charge"></td>
                    </tr>

                    <tr><td>Medicine Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="medicine_charge_input" name="medicine_charge"></td>
                    </tr>

                    <tr><td>Test Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="test_charge_input" name="test_charge"></td>
                    </tr>

                    <!-- OTHER FIXED CHARGES -->
                    <tr><td>Nurse Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="nurse_charge" name="nurse_charge"></td>
                    </tr>

                    <tr><td>Service Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="service_charge" name="service_charge"></td>
                    </tr>

                    <tr><td>Equipment Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="equipment_charge" name="equipment_charge"></td>
                    </tr>

                    <tr><td>OT Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="ot_charge" name="ot_charge"></td>
                    </tr>

                    <tr><td>Lab Tech Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="lab_tech_charge" name="lab_tech_charge"></td>
                    </tr>

                    <tr><td>Ambulance Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="ambulance_charge" name="ambulance_charge"></td>
                    </tr>

                    <tr><td>Food Charge</td>
                        <td><input type="number" step="0.01" class="form-control" id="food_charge" name="food_charge"></td>
                    </tr>

                    <!-- OTHER CHARGES DYNAMIC -->
                    <tr>
                        <td colspan="2">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>Other Charges</strong>
                                <button type="button" class="btn btn-primary btn-sm" id="add-other-charge">+ Add</button>
                            </div>
                            <table class="table" id="other-charges-table">
                                <thead>
                                    <tr><th>Name</th><th>Amount</th><th></th></tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- SUBTOTAL -->
                    <tr>
                        <td>Subtotal</td>
                        <td><input type="text" class="form-control" id="subtotal" readonly></td>
                    </tr>

                    <!-- DISCOUNT -->
                    <tr>
                        <td>
                            Discount  
                            <select id="discount_type" class="form-control mt-1" name="discount_type">
                                <option value="value">Value</option>
                                <option value="percentage">Percent</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" id="discount" name="discount" class="form-control">
                            <input type="hidden" name="discount_amount" id="discount_amount">
                        </td>
                    </tr>

                    <!-- TOTAL -->
                    <tr>
                        <td>Total</td>
                        <td><input type="text" id="total" name="total" class="form-control" readonly></td>
                    </tr>

                    <tr>
                        <td>Paid</td>
                        <td><input type="number" step="0.01" id="paid" class="form-control" name="paid"></td>
                    </tr>

                    <tr>
                        <td>Due</td>
                        <td><input type="text" id="due" name="due" class="form-control" readonly></td>
                    </tr>

                    <!-- PAYMENT -->
                    <tr>
                        <td>Payment Type</td>
                        <td>
                            <select name="payment_type" class="form-control">
                                <option value="cash">Cash</option>
                                <option value="upi">UPI</option>
                                <option value="card">Card</option>
                                <option value="netbanking">Net Banking</option>
                                <option value="other">Other</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Notes</td>
                        <td><textarea name="notes" class="form-control ckeditor"></textarea></td>
                    </tr>

                    <tr>
                        <td>Attachments</td>
                        <td>
                            <div class="dropzone" id="attechments-dropzone"></div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <button class="btn btn-lg btn-success px-5 py-2" style="font-size:18px;border-radius:10px;">
                💾 Save Billing
            </button>
        </div>

    </form>

</div>

@endsection

@section('scripts')
<script>

function formatCurrency(v){ 
    return parseFloat(v || 0).toFixed(2); 
}

window.previousDue = 0; // store previous due globally


/* RECALCULATE TOTALS */
function recalcTotals(){

    let doctor = parseFloat($('#doctor_charge').val() || 0);
    let room = parseFloat($('#room_charge_input').val() || 0);
    let med = parseFloat($('#medicine_charge_input').val() || 0);
    let test = parseFloat($('#test_charge_input').val() || 0);
    let nurse = parseFloat($('#nurse_charge').val() || 0);
    let service = parseFloat($('#service_charge').val() || 0);
    let equipment = parseFloat($('#equipment_charge').val() || 0);
    let ot = parseFloat($('#ot_charge').val() || 0);
    let labtech = parseFloat($('#lab_tech_charge').val() || 0);
    let ambulance = parseFloat($('#ambulance_charge').val() || 0);
    let food = parseFloat($('#food_charge').val() || 0);

    let otherTotal = 0;
    $('#other-charges-table tbody tr').each(function(){
        let amt = parseFloat($(this).find('.other-amount').val() || 0);
        otherTotal += amt;
    });

    let subtotal = doctor + room + med + test + nurse + service + equipment + ot + labtech + ambulance + food + otherTotal;
    $('#subtotal').val(formatCurrency(subtotal));
    $('#subtotal_display').text(formatCurrency(subtotal));

    let discountType = $('#discount_type').val();
    let discount = parseFloat($('#discount').val() || 0);

    let discountAmount = (discountType === 'percentage')
        ? (subtotal * discount) / 100
        : discount;

    $('#discount_amount').val(formatCurrency(discountAmount));

    let total = subtotal - discountAmount;
    $('#total').val(formatCurrency(total));

    /* 🟢 APPLY PREVIOUS DUE LOGIC IF EXISTS */
    if ($('#paid').data('lock') === true) {

        let newDue = window.previousDue - discountAmount;
        if (newDue < 0) newDue = 0;   // due kabhi negative nahi hoga

        $('#due').val(formatCurrency(newDue));
        $('#paid').val(formatCurrency(newDue));

        return; // prevent normal calculation
    }

    /* Normal case (no previous due) */
    let paid = parseFloat($('#paid').val() || 0);
    $('#due').val(formatCurrency(total - paid));
}


/* WHEN AMOUNT OR DISCOUNT FIELDS CHANGE */
$(document).on('input change',
    '#doctor_charge, #room_charge_input, #medicine_charge_input, #test_charge_input, #nurse_charge, #service_charge, #equipment_charge, #ot_charge, #lab_tech_charge, #ambulance_charge, #food_charge, #discount, #paid, #discount_type',
    recalcTotals
);


/* OTHER CHARGE ROWS */
$('#add-other-charge').click(function(){
    $('#other-charges-table tbody').append(`
        <tr>
            <td><input type="text" name="other_charges[][name]" class="form-control" required></td>
            <td style="width:180px">
                <input type="number" step="0.01" name="other_charges[][amount]" class="form-control other-amount" value="0" required>
            </td>
            <td style="width:80px"><button type="button" class="btn btn-danger btn-sm remove-other">Remove</button></td>
        </tr>
    `);
});
$(document).on('click', '.remove-other', function(){
    $(this).closest('tr').remove();
    recalcTotals();
});
$(document).on('input', '.other-amount', recalcTotals);



/* FETCH AUTO DETAILS & PREVIOUS PAYMENTS */
$('#ipd_id').change(function () {

    let id = $(this).val();
    if (!id) return;

    /* FETCH IPD DETAILS */
    $.ajax({
        url: "{{ route('admin.ipd-billings.getBillingDetails') }}",
        data: { ipd_id: id },
        success: function(res) {

            $('#ipd-cards').show();

            $('#p_name').text(res.patient?.patient_name || '-');
            $('#p_mobile').text(res.patient?.mobile_number || '-');
            $('#p_reason').text(res.patient?.reason_for_visit || '-');
            $('#p_admission_date').text(res.ipd.admission_date || '-');
            $('#p_days').text(res.days || 1);

            $('#d_name').text(res.doctor?.doctor_name || '-');
            $('#d_department').text(res.doctor?.doctor_department || '-');
            $('#d_fee').text(res.doctor?.doctor_fee || '0');
            $('#d_charge').text(formatCurrency(res.doctor_charge || 0));

            $('#room_charge').text(formatCurrency(res.room_charge || 0));
            $('#medicine_charge').text(formatCurrency(res.medicine_charge || 0));
            $('#test_charge').text(formatCurrency(res.test_charge || 0));

            $('#doctor_charge').val(formatCurrency(res.doctor_charge));
            $('#room_charge_input').val(formatCurrency(res.room_charge));
            $('#medicine_charge_input').val(formatCurrency(res.medicine_charge));
            $('#test_charge_input').val(formatCurrency(res.test_charge));

            recalcTotals();
        }
    });


    /* FETCH PAST PAYMENTS */
    $.ajax({
        url: "{{ route('admin.ipd-billings.getPastPayments') }}",
        data: { ipd_id: id },
        success: function(res) {

            $('#previous-payment-box').show();
            $('#prev_total_billed').text(res.total_billed.toFixed(2));
            $('#prev_total_paid').text(res.total_paid.toFixed(2));
            $('#prev_due').text(res.due.toFixed(2));
            $('#prev_message').text(res.message);

            if (res.due > 0) {

                window.previousDue = parseFloat(res.due); // store it

                $('#paid').data('lock', true);

                $('#paid').val(formatCurrency(window.previousDue));
                $('#due').val(formatCurrency(window.previousDue));

                $('#paid').prop("readonly", true);

            } else {

                window.previousDue = 0;

                $('#paid').data('lock', false);
                $('#paid').val(0);
                $('#due').val(0);
                $('#paid').prop("readonly", true);

                $('#prev_message').text("Fully Paid – No Due Left");
            }
        }
    });

});


/* CKEDITOR & DROPZONE CODE REMAINS SAME */
var uploadedAttechmentsMap = {}
Dropzone.options.attechmentsDropzone = {
    url: '{{ route('admin.ipd-billings.storeMedia') }}',
    maxFilesize: 20,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 20 },
    success: function (file, response) {
        $('form').append('<input type="hidden" name="attechments[]" value="' + response.name + '">')
        uploadedAttechmentsMap[file.name] = response.name
    },
    removedfile: function (file) {
        file.previewElement.remove()
        var name = uploadedAttechmentsMap[file.name]
        $('form').find('input[name="attechments[]"][value="' + name + '"]').remove()
    },
    error: function (file, response) {
        var message = $.type(response) === 'string' ? response : response.errors.file
        file.previewElement.classList.add('dz-error')
        var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        for (var i = 0; i < _ref.length; i++) {
            _ref[i].textContent = message
        }
    }
}

</script>
@endsection
