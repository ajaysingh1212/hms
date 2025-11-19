
@extends('layouts.admin')
@section('content')

<style>
.billing-card{
    background:#ffffff;
    padding:20px;
    box-shadow:0 5px 25px rgba(0,0,0,0.08);
    border:1px solid #eef1f7;
}
.billing-title{
    font-size:20px;
    color:#2c3e50;
    font-weight:700;
    border-bottom:2px solid #f1f1f1;
    margin-bottom:15px;
    padding-bottom:8px;
}
.info-cards{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
    margin-bottom:25px;
}
.info-box{
    flex:1 1 calc(25% - 15px);
    background:#fafcff;
    padding:15px;
    border-radius:10px;
    border:1px solid #ebedf3;
    min-width:250px;
}
.info-box h5{
    margin-bottom:8px;
    font-size:16px;
    color:#34495e;
}
.amount{
    font-size:20px;
    font-weight:700;
    color:#2980b9;
}
.total-section{
    background:#F8FBFF;
    padding:20px;
    border-radius:12px;
    border:2px dashed #dfe7f3;
}
</style>



<div class="billing-card">
    <div class="billing-title">
        🏥 OPD Billing (Hospital-Grade UI)
    </div>

    <form method="POST" action="{{ route('admin.opd-billings.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- OPD Select --}}
        <div class="form-group">
            <label>Select OPD</label>
            <select class="form-control select2" id="opd_id" name="opd_id">
                <option value="">Select</option>
                @foreach($opds as $id => $entry)
                    <option value="{{ $id }}">{{ $entry }}</option>
                @endforeach
            </select>
        </div>


        {{-- CARDS --}}
        <div class="info-cards">

            {{-- Doctor --}}
            <div class="info-box">
                <h5>Doctor</h5>
                <p><strong>Name:</strong> <span id="doctor_name">--</span></p>
                <p><strong>Charge:</strong> <span class="amount" id="doctor_fee">0</span></p>
            </div>

            {{-- Medicines --}}
            <div class="info-box">
                <h5>Medicines</h5>
                <div id="medicine_list">No medicines</div>
                <p><strong>Total:</strong> <span class="amount" id="medicine_total">0</span></p>
            </div>

            {{-- Tests --}}
            <div class="info-box">
                <h5>Tests</h5>
                <div id="test_list">No tests</div>
                <p><strong>Total:</strong> <span class="amount" id="test_total">0</span></p>
            </div>

            {{-- Grand Total --}}
            <div class="info-box">
                <h5>Grand Total</h5>
                <p class="amount" id="grand_total">0</p>
            </div>

        </div>


        {{-- Discount + Paid + Due --}}
        <div class="total-section">

            <div class="form-group">
                <label>Discount Type</label>
                <select class="form-control" name="discount_type" id="discount_type">
                    <option value="">Select Type</option>
                    <option value="percent">Percentage (%)</option>
                    <option value="value">Fixed Amount</option>
                </select>
            </div>

            <div class="form-group">
                <label>Discount</label>
                <input type="number" id="discount" class="form-control" placeholder="Enter discount">
            </div>

            <div class="form-group">
                <label>Paid Amount</label>
                <input type="number" name="paid" id="paid" class="form-control" placeholder="Enter paid amount">
            </div>

            <div class="form-group">
                <label>Due Amount</label>
                <input type="number" name="due" id="due" class="form-control" readonly>
            </div>

            <input type="hidden" id="total" name="total">

        </div>



        {{-- Attachments --}}
        <div class="form-group">
            <label>Attachments</label>
            <div class="needsclick dropzone" id="attechment-dropzone"></div>
        </div>


        {{-- Notes --}}
        <div class="form-group">
            <label>Notes</label>
            <textarea class="form-control ckeditor" name="notes"></textarea>
        </div>
        <div class="form-group">
                <label>{{ trans('cruds.opdBilling.fields.payment_type') }}</label>
                <select class="form-control {{ $errors->has('payment_type') ? 'is-invalid' : '' }}" name="payment_type" id="payment_type">
                    <option value disabled {{ old('payment_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\OpdBilling::PAYMENT_TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.opdBilling.fields.payment_type_helper') }}</span>
            </div>
        <button class="btn btn-primary btn-lg" type="submit">Submit Bill</button>

    </form>
</div>


@endsection



@section('scripts')
<script>
$('#opd_id').on('change', function () {

    var opdId = $(this).val();
    if (!opdId) return;

    $.ajax({
        url: "{{ route('admin.opd-billings.opdDetails') }}",
        type: "GET",
        data: { opd_id: opdId },
        success: function(res){

            // Doctor
            $('#doctor_name').text(res.doctor);
            $('#doctor_fee').text(res.doctor_fee);

            // Medicines
            let medHtml = "";
            res.medicines.forEach(m => {
                medHtml += `<div>${m.name} - ₹${m.price}</div>`;
            });
            $('#medicine_list').html(medHtml || "No medicines");
            $('#medicine_total').text(res.medicine_total);


            // ⭐⭐⭐ TESTS – FIXED ⭐⭐⭐
            let testHtml = "";
            res.tests.forEach(t => {
                testHtml += `<div>${t.name} - ₹${t.price}</div>`;
            });
            $('#test_list').html(testHtml || "No tests");
            $('#test_total').text(res.test_total);


            calculateTotal();
        }
    });

});


// Auto Calculation
function calculateTotal() {
    var doctor = parseFloat($('#doctor_fee').text()) || 0;
    var med = parseFloat($('#medicine_total').text()) || 0;
    var test = parseFloat($('#test_total').text()) || 0;

    var total = doctor + med + test;

    var discountType = $('#discount_type').val();
    var discount = parseFloat($('#discount').val()) || 0;

    if (discountType == "percent") total -= (total * discount / 100);
    if (discountType == "value") total -= discount;

    $('#grand_total').text(total);
    $('#total').val(total);

    var paid = parseFloat($('#paid').val()) || 0;
    $('#due').val(total - paid);
}

$('#discount, #discount_type, #paid').on('input change', calculateTotal);
</script>





{{-- Dropzone --}}
<script>
    var uploadedAttechmentMap = {}
Dropzone.options.attechmentDropzone = {
    url: '{{ route('admin.opd-billings.storeMedia') }}',
    maxFilesize: 20,
    addRemoveLinks: true,
    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
    params: { size: 20 },

    success: function (file, response) {
      $('form').append('<input type="hidden" name="attechment[]" value="' + response.name + '">')
      uploadedAttechmentMap[file.name] = response.name
    },

    removedfile: function (file) {
      file.previewElement.remove()
      var name = file.file_name || uploadedAttechmentMap[file.name];
      $('form').find('input[name="attechment[]"][value="' + name + '"]').remove()
    },

    init: function () {
    @if(isset($opdBilling) && $opdBilling->attechment)
        var files = {!! json_encode($opdBilling->attechment) !!}
        for (var i in files) {
            var file = files[i]
            this.options.addedfile.call(this, file)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="attechment[]" value="' + file.file_name + '">')
        }
    @endif
    }
}
</script>



{{-- CKEditor --}}
<script>
$(document).ready(function () {

  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file.then(function (file) {
            return new Promise(function(resolve, reject) {

              var xhr = new XMLHttpRequest();
              xhr.open('POST', '{{ route('admin.opd-billings.storeCKEditorImages') }}', true);
              xhr.setRequestHeader('x-csrf-token', window._token);
              xhr.setRequestHeader('Accept', 'application/json');
              xhr.responseType = 'json';

              xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                      return reject(`Couldn't upload file: ${ file.name }.`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
              });

              var data = new FormData();
              data.append('upload', file);
              data.append('crud_id', '{{ $opdBilling->id ?? 0 }}');
              xhr.send(data);

            });
          })
        }
      };
    };
  }

  document.querySelectorAll('.ckeditor').forEach((el) => {
    ClassicEditor.create(el, { extraPlugins: [SimpleUploadAdapter] });
  });

});
</script>

@endsection
