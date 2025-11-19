@extends('layouts.admin')
@section('content')

<style>
    .custom-card { border-radius:10px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
    .info-badge { font-size:13px; padding:6px 10px; border-radius:20px; background:#eef7ff; color:#1e6fd8; }
    .dropzone { border:2px dashed #1e6fd8 !important; border-radius:10px !important; background:#fbfdff !important; padding:22px !important; }
    .dropzone .dz-message { font-size:18px !important; color:#1e6fd8 !important; }
    .card-header-compact { padding:10px 14px; font-weight:600; }
    .small-muted { font-size:13px; color:#6c757d; }
    .charges-table td { vertical-align: middle; }
</style>

<div class="card custom-card">
    <div class="card-header bg-primary text-white card-header-compact">
        <strong>{{ trans('global.edit') }} {{ trans('cruds.ipdBilling.title_singular') }}</strong>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.ipd-billings.update', [$ipdBilling->id]) }}" enctype="multipart/form-data" id="billing-form">
            @method('PUT')
            @csrf

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="ipd_id">{{ trans('cruds.ipdBilling.fields.ipd') }}</label>
                        <select class="form-control select2" name="ipd_id" id="ipd_id">
                            @foreach($ipds as $id => $entry)
                                <option value="{{ $id }}" {{ (old('ipd_id') ? old('ipd_id') == $id : $ipdBilling->ipd_id == $id) ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-8 d-flex align-items-center">
                    <div class="w-100 small-muted">Select IPD to auto-calc charges from admission date to today</div>
                </div>
            </div>

            <div id="ipd-cards" style="display:none;" class="row mt-3">
                <div class="col-lg-4 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-info text-white card-header-compact">Patient</div>
                        <div class="card-body">
                            <p><span class="info-badge">Name</span> <strong id="p_name" class="ml-2"></strong></p>
                            <p><span class="info-badge">Mobile</span> <strong id="p_mobile" class="ml-2"></strong></p>
                            <p class="small-muted"><b>Reason:</b> <span id="p_reason"></span></p>
                            <p class="small-muted"><b>Admission Date:</b> <span id="p_admission_date"></span></p>
                            <p class="small-muted"><b>Days:</b> <span id="p_days"></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-success text-white card-header-compact">Doctor</div>
                        <div class="card-body">
                            <p><span class="info-badge">Name</span> <strong id="d_name" class="ml-2"></strong></p>
                            <p class="small-muted"><b>Department:</b> <span id="d_department"></span></p>
                            <p class="small-muted"><b>Fee (per day):</b> ₹<span id="d_fee"></span></p>
                            <p class="small-muted"><b>Doctor Charge:</b> ₹<span id="d_charge"></span></p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-3">
                    <div class="card custom-card">
                        <div class="card-header bg-warning card-header-compact">Charges Summary</div>
                        <div class="card-body">
                            <p class="small-muted"><b>Room Charge:</b> ₹<span id="room_charge"></span></p>
                            <p class="small-muted"><b>Medicine Charge:</b> ₹<span id="medicine_charge"></span></p>
                            <p class="small-muted"><b>Test Charge:</b> ₹<span id="test_charge"></span></p>
                            <p class="small-muted"><b>Subtotal:</b> ₹<span id="subtotal_display"></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-6">
                    <table class="table table-bordered charges-table">
                        <tbody>
                            <tr>
                                <td style="width:55%"><label>Doctor Charge</label></td>
                                <td><input type="number" step="0.01" name="doctor_charge" id="doctor_charge" class="form-control" value="{{ old('doctor_charge', $ipdBilling->doctor_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Room Charge</label></td>
                                <td><input type="number" step="0.01" name="room_charge" id="room_charge_input" class="form-control" value="{{ old('room_charge', $ipdBilling->room_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Medicine Charge</label></td>
                                <td><input type="number" step="0.01" name="medicine_charge" id="medicine_charge_input" class="form-control" value="{{ old('medicine_charge', $ipdBilling->medicine_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Test Charge</label></td>
                                <td><input type="number" step="0.01" name="test_charge" id="test_charge_input" class="form-control" value="{{ old('test_charge', $ipdBilling->test_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Nurse Charge</label></td>
                                <td><input type="number" step="0.01" name="nurse_charge" id="nurse_charge" class="form-control" value="{{ old('nurse_charge', $ipdBilling->nurse_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Service Charge</label></td>
                                <td><input type="number" step="0.01" name="service_charge" id="service_charge" class="form-control" value="{{ old('service_charge', $ipdBilling->service_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Equipment Charge</label></td>
                                <td><input type="number" step="0.01" name="equipment_charge" id="equipment_charge" class="form-control" value="{{ old('equipment_charge', $ipdBilling->equipment_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>OT Charge</label></td>
                                <td><input type="number" step="0.01" name="ot_charge" id="ot_charge" class="form-control" value="{{ old('ot_charge', $ipdBilling->ot_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Lab Tech Charge</label></td>
                                <td><input type="number" step="0.01" name="lab_tech_charge" id="lab_tech_charge" class="form-control" value="{{ old('lab_tech_charge', $ipdBilling->lab_tech_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Ambulance Charge</label></td>
                                <td><input type="number" step="0.01" name="ambulance_charge" id="ambulance_charge" class="form-control" value="{{ old('ambulance_charge', $ipdBilling->ambulance_charge) }}"></td>
                            </tr>
                            <tr>
                                <td><label>Food Charge</label></td>
                                <td><input type="number" step="0.01" name="food_charge" id="food_charge" class="form-control" value="{{ old('food_charge', $ipdBilling->food_charge) }}"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>Other Charges</strong>
                                        <button type="button" class="btn btn-sm btn-primary" id="add-other-charge">Add Charge</button>
                                    </div>
                                    <table class="table mt-2" id="other-charges-table">
                                        <thead>
                                            <tr>
                                                <th>Charge Name</th>
                                                <th>Amount</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $others = json_decode($ipdBilling->other_charges, true) ?? []; @endphp
                                            @foreach($others as $oc)
                                                <tr>
                                                    <td><input type="text" name="other_charges[][name]" class="form-control other-name" value="{{ $oc['name'] ?? '' }}"></td>
                                                    <td style="width:180px"><input type="number" step="0.01" name="other_charges[][amount]" class="form-control other-amount" value="{{ $oc['amount'] ?? 0 }}"></td>
                                                    <td style="width:80px"><button type="button" class="btn btn-danger btn-sm remove-other">Remove</button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Subtotal</label></td>
                                <td><input type="text" readonly id="subtotal" class="form-control" value="{{ $ipdBilling->subtotal }}"></td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Discount</label>
                                    <select id="discount_type" name="discount_type" class="form-control mt-1">
                                        <option value="value" {{ $ipdBilling->discount_type === 'value' ? 'selected' : '' }}>Value (Amount)</option>
                                        <option value="percentage" {{ $ipdBilling->discount_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="discount" id="discount" class="form-control" value="{{ $ipdBilling->discount }}">
                                    <input type="hidden" name="discount_amount" id="discount_amount" value="{{ $ipdBilling->discount_amount }}">
                                </td>
                            </tr>
                            <tr>
                                <td><label>Total</label></td>
                                <td><input type="text" readonly name="total" id="total" class="form-control" value="{{ $ipdBilling->total }}"></td>
                            </tr>
                            <tr>
                                <td><label>Paid</label></td>
                                <td><input type="number" step="0.01" name="paid" id="paid" class="form-control" value="{{ $ipdBilling->paid }}"></td>
                            </tr>
                            <tr>
                                <td><label>Due</label></td>
                                <td><input type="text" readonly name="due" id="due" class="form-control" value="{{ $ipdBilling->due }}"></td>
                            </tr>
                            <tr>
                                <td><label>Payment Type</label></td>
                                <td>
                                    <select name="payment_type" id="payment_type" class="form-control">
                                        <option value="cash" {{ $ipdBilling->payment_type === 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="upi" {{ $ipdBilling->payment_type === 'upi' ? 'selected' : '' }}>Upi</option>
                                        <option value="card" {{ $ipdBilling->payment_type === 'card' ? 'selected' : '' }}>Card</option>
                                        <option value="netbanking" {{ $ipdBilling->payment_type === 'netbanking' ? 'selected' : '' }}>Net Banking</option>
                                        <option value="other" {{ $ipdBilling->payment_type === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Notes</label></td>
                                <td><textarea name="notes" id="notes" class="form-control ckeditor">{{ $ipdBilling->notes }}</textarea></td>
                            </tr>
                            <tr>
                                <td><label>Attachments</label></td>
                                <td>
                                    <div class="needsclick dropzone" id="attechments-dropzone"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-danger btn-lg px-4" type="submit">Update Billing</button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function formatCurrency(v){ return parseFloat(v || 0).toFixed(2); }

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
    let discountAmount = 0;
    if(discountType === 'percentage'){
        discountAmount = (subtotal * discount) / 100;
    } else {
        discountAmount = discount;
    }
    $('#discount_amount').val(formatCurrency(discountAmount));
    let total = subtotal - discountAmount;
    $('#total').val(formatCurrency(total));
    let paid = parseFloat($('#paid').val() || 0);
    let due = total - paid;
    $('#due').val(formatCurrency(due));
}

$(document).on('input change', '#doctor_charge, #room_charge_input, #medicine_charge_input, #test_charge_input, #nurse_charge, #service_charge, #equipment_charge, #ot_charge, #lab_tech_charge, #ambulance_charge, #food_charge, #discount, #paid, #discount_type', function(){
    recalcTotals();
});

$('#add-other-charge').on('click', function(){
    let row = `<tr>
        <td><input type="text" name="other_charges[][name]" class="form-control other-name" required></td>
        <td style="width:180px"><input type="number" step="0.01" name="other_charges[][amount]" class="form-control other-amount" value="0" required></td>
        <td style="width:80px"><button type="button" class="btn btn-danger btn-sm remove-other">Remove</button></td>
    </tr>`;
    $('#other-charges-table tbody').append(row);
});

$(document).on('click', '.remove-other', function(){ $(this).closest('tr').remove(); recalcTotals(); });
$(document).on('input', '.other-amount', function(){ recalcTotals(); });

function loadIpdDetails(id) {
    if (!id) return;
    $.ajax({
        url: "{{ route('admin.ipd-billings.getBillingDetails') }}",
        data: { ipd_id: id },
        success: function(res) {
            $('#ipd-cards').show();
            $('#p_name').text(res.patient ? (res.patient.patient_name || '-') : '-');
            $('#p_mobile').text(res.patient ? (res.patient.mobile_number || '-') : '-');
            $('#p_reason').text(res.patient ? (res.patient.reason_for_visit || '-') : '-');
            $('#p_admission_date').text(res.ipd.admission_date || '-');
            $('#p_days').text(res.days || 1);
            $('#d_name').text(res.doctor ? (res.doctor.doctor_name || '-') : '-');
            $('#d_department').text(res.doctor ? (res.doctor.doctor_department || '-') : '-');
            $('#d_fee').text(res.doctor ? (res.doctor.doctor_fee || 0) : 0);
            $('#d_charge').text(formatCurrency(res.doctor_charge || 0));
            $('#room_charge').text(formatCurrency(res.room_charge || 0));
            $('#medicine_charge').text(formatCurrency(res.medicine_charge || 0));
            $('#test_charge').text(formatCurrency(res.test_charge || 0));
            $('#doctor_charge').val(formatCurrency(res.doctor_charge || 0));
            $('#room_charge_input').val(formatCurrency(res.room_charge || 0));
            $('#medicine_charge_input').val(formatCurrency(res.medicine_charge || 0));
            $('#test_charge_input').val(formatCurrency(res.test_charge || 0));
            recalcTotals();
        }
    });
}

$('#ipd_id').change(function () {
    let id = $(this).val();
    loadIpdDetails(id);
});

$(document).ready(function () {
  let initialIpd = '{{ old('ipd_id', $ipdBilling->ipd_id) }}';
  if (initialIpd) {
    $('#ipd-cards').show();
    loadIpdDetails(initialIpd);
  }

  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file.then(function (file) {
            return new Promise(function(resolve, reject) {
              var xhr = new XMLHttpRequest();
              xhr.open('POST', '{{ route('admin.ipd-billings.storeCKEditorImages') }}', true);
              xhr.setRequestHeader('x-csrf-token', window._token);
              xhr.setRequestHeader('Accept', 'application/json');
              xhr.responseType = 'json';
              xhr.addEventListener('error', function() { reject('Upload failed'); });
              xhr.addEventListener('abort', function() { reject() });
              xhr.addEventListener('load', function() {
                var response = xhr.response;
                if (!response || xhr.status !== 201) {
                  return reject('Upload failed');
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
              data.append('crud_id', '{{ $ipdBilling->id ?? 0 }}');
              xhr.send(data);
            });
          })
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

<script>
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
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttechmentsMap[file.name]
      }
      $('form').find('input[name="attechments[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($ipdBilling) && $ipdBilling->attechments)
      var files = {!! json_encode($ipdBilling->attechments) !!}
      for (var i in files) {
        var file = files[i]
        this.options.addedfile.call(this, file)
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="attechments[]" value="' + file.file_name + '">')
      }
@endif
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
