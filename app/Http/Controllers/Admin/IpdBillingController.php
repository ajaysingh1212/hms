<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdBillingRequest;
use App\Http\Requests\StoreIpdBillingRequest;
use App\Http\Requests\UpdateIpdBillingRequest;
use App\Models\IpdAdmission;
use App\Models\IpdBilling;
use App\Models\IpdMedication;
use App\Models\IpdTest;
use App\Models\IpdVital;
use App\Models\AddDoctor;
use App\Models\IpdRoom;
use App\Models\IpdBed;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdBillingController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_billing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBillings = IpdBilling::with(['ipd', 'created_by', 'media'])->get();

        return view('admin.ipdBillings.index', compact('ipdBillings'));
    }

    public function getBillingDetails(Request $request)
    {
        $ipd_id = $request->ipd_id;

        $ipd = DB::table('ipd_admissions')->where('id', $ipd_id)->first();
        if (!$ipd) {
            return response()->json(['error' => true]);
        }

        // PATIENT
        $patient = DB::table('appointments')
            ->leftJoin('department_names', 'appointments.department_id', '=', 'department_names.id')
            ->select(
                'appointments.patient_name',
                'appointments.mobile_number',
                'appointments.reason_for_visit',
                'department_names.name as department_name'
            )
            ->where('appointments.id', $ipd->patient_id)
            ->first();

        // DOCTOR
        $doctor = DB::table('add_doctors')
            ->leftJoin('department_names', 'add_doctors.select_department_id', '=', 'department_names.id')
            ->select(
                'add_doctors.doctor_name',
                'add_doctors.doctor_fee',
                'add_doctors.experience',
                'add_doctors.qualifications',
                'add_doctors.phone',
                'department_names.name as doctor_department'
            )
            ->where('add_doctors.id', $ipd->doctor_id)
            ->first();

        // ROOM & BED
        $room = DB::table('ipd_rooms')->select('room_no', 'ward_type')->where('id', $ipd->room_id)->first();
        $bed = DB::table('ipd_beds')->select('bed_no', 'charges_per_day')->where('id', $ipd->bed_id)->first();

        // DAYS CALCULATION
        $admission_date = $ipd->admission_date ?? null;
        $days = 1;

        if ($admission_date) {
            try {
                $days = Carbon::parse($admission_date)->diffInDays(Carbon::today()) + 1;
            } catch (\Exception $e) { 
                $days = 1;
            }
        }

        // CHARGES
        $doctor_charge = $doctor ? ((float)$doctor->doctor_fee * $days) : 0;
        $room_charge   = $bed ? ((float)$bed->charges_per_day * $days) : 0;  // Only Bed charge as room rent

        // MEDICINE CHARGE
        $latestMedications = IpdMedication::where('ipd_id', $ipd_id)->with('medicines')->get();

        $medicine_charge = 0;
        foreach ($latestMedications as $med) {
            foreach ($med->medicines as $m) {
                $medicine_charge += (float)$m->price;  // FIXED
            }
        }

        // TEST CHARGE
        $ipdTests = IpdTest::where('ipd_id', $ipd_id)->with('tests')->get();

        $test_charge = 0;
        foreach ($ipdTests as $t) {
            foreach ($t->tests as $lt) {
                $test_charge += (float)$lt->price;  // FIXED
            }
        }

        // VITALS
        $vitals = IpdVital::where('ipd_id', $ipd_id)
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'date_time' => $v->date_time,
                    'temperature' => $v->temperature,
                    'pulse' => $v->pulse,
                    'bp' => $v->bp,
                    'spo_2' => $v->spo_2,
                    'respiration' => $v->respiration,
                    'notes' => $v->notes,
                ];
            });

        // MEDICINES LIST
        $meds = [];
        foreach ($latestMedications as $lm) {
            foreach ($lm->medicines as $m) {
                $meds[] = [
                    'id'    => $m->id,
                    'name'  => $m->name,
                    'price' => (float)$m->price  // FIXED
                ];
            }
        }

        // TEST LIST
        $tests = [];
        foreach ($ipdTests as $it) {
            foreach ($it->tests as $tt) {
                $tests[] = [
                    'id'    => $tt->id,
                    'name'  => $tt->test_name,
                    'price' => (float)$tt->price  // FIXED
                ];
            }
        }

        // FINAL TOTAL
        $subtotal = $doctor_charge + $room_charge + $medicine_charge + $test_charge;

        return response()->json([
            'ipd' => $ipd,
            'patient' => $patient,
            'doctor' => $doctor,
            'room' => $room,
            'bed' => $bed,
            'days' => $days,

            'doctor_charge' => $doctor_charge,
            'room_charge' => $room_charge,
            'medicine_charge' => $medicine_charge,
            'test_charge' => $test_charge,

            'subtotal' => $subtotal,

            'medicines' => $meds,
            'tests' => $tests,
            'vitals' => $vitals
        ]);
    }
    public function getPastPayments(Request $request)
{
    $ipdId = $request->ipd_id;

    // Fetch all billings of this IPD
    $billings = IpdBilling::where('ipd_id', $ipdId)->get();

    if ($billings->count() == 0) {
        return response()->json([
            'exists' => false,
            'message' => 'No previous payments found.',
            'total_billed' => 0,
            'total_paid' => 0,
            'due' => 0,
        ]);
    }

    $totalBilled = $billings->sum('total');
    $totalPaid = $billings->sum('paid');
    $due = $totalBilled - $totalPaid;

    return response()->json([
        'exists' => true,
        'message' => $due > 0 ? 'Previous due exists' : 'Full payment already done',
        'total_billed' => $totalBilled,
        'total_paid' => $totalPaid,
        'due' => $due,
    ]);
}


    public function create()
    {
        abort_if(Gate::denies('ipd_billing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdBillings.create', compact('ipds'));
    }

    public function store(StoreIpdBillingRequest $request)
    {
        // Start with the request array (you can still use $data to save)
        $data = $request->all();

        // Ensure other_charges is always an array (and store JSON)
        $otherCharges = $request->input('other_charges', []); // array of ['name'=>..., 'amount'=>...]
        $data['other_charges'] = json_encode($otherCharges);

        // Safely read numeric fields with defaults
        $doctorCharge     = (float) $request->input('doctor_charge', 0);
        $roomCharge       = (float) $request->input('room_charge', 0);
        $medicineCharge   = (float) $request->input('medicine_charge', 0);
        $testCharge       = (float) $request->input('test_charge', 0);
        $nurseCharge      = (float) $request->input('nurse_charge', 0);
        $serviceCharge    = (float) $request->input('service_charge', 0);
        $equipmentCharge  = (float) $request->input('equipment_charge', 0);
        $otCharge         = (float) $request->input('ot_charge', 0);
        $labTechCharge    = (float) $request->input('lab_tech_charge', 0);
        $ambulanceCharge  = (float) $request->input('ambulance_charge', 0);
        $foodCharge       = (float) $request->input('food_charge', 0);

        // Sum other charges safely
        $otherTotal = 0.0;
        if (is_array($otherCharges)) {
            foreach ($otherCharges as $oc) {
                $otherTotal += (float) ($oc['amount'] ?? 0);
            }
        }

        // Calculate subtotal
        $subtotal = $doctorCharge + $roomCharge + $medicineCharge + $testCharge
                    + $nurseCharge + $serviceCharge + $equipmentCharge + $otCharge
                    + $labTechCharge + $ambulanceCharge + $foodCharge + $otherTotal;

        // Save subtotal in data
        $data['subtotal'] = $subtotal;

        // Discount handling: read type and value safely
        $discountType = $request->input('discount_type', 'value'); // 'value' or 'percentage'
        $discountValue = (float) $request->input('discount', 0);

        if ($discountType === 'percentage') {
            $discountAmount = ($subtotal * $discountValue) / 100.0;
        } else {
            $discountAmount = $discountValue;
        }
        $data['discount_amount'] = $discountAmount;
        $data['discount_type'] = $discountType;
        // store raw discount value if you want
        $data['discount'] = $discountValue;

        // total and due
        $data['total'] = $subtotal - $discountAmount;
        $paid = (float) $request->input('paid', 0);
        $data['paid'] = $paid;
        $data['due'] = $data['total'] - $paid;

        // Create the record
        $ipdBilling = IpdBilling::create($data);

        // Attach files (if any) from temporary uploads
        foreach ($request->input('attechments', []) as $file) {
            // Protect against empty values
            if ($file) {
                $ipdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
            }
        }

        // Associate CKEditor media (if provided)
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdBilling->id]);
        }

        return redirect()->route('admin.ipd-billings.index');
    }

    public function edit(IpdBilling $ipdBilling)
    {
        abort_if(Gate::denies('ipd_billing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdBilling->load('ipd', 'created_by');

        return view('admin.ipdBillings.edit', compact('ipdBilling', 'ipds'));
    }

    public function update(UpdateIpdBillingRequest $request, IpdBilling $ipdBilling)
    {
        $data = $request->all();
        $data['other_charges'] = json_encode($request->input('other_charges', []));
        $subtotal = 0;
        $subtotal += (float)$data['doctor_charge'] ?? 0;
        $subtotal += (float)$data['room_charge'] ?? 0;
        $subtotal += (float)$data['medicine_charge'] ?? 0;
        $subtotal += (float)$data['test_charge'] ?? 0;
        $subtotal += (float)$data['nurse_charge'] ?? 0;
        $subtotal += (float)$data['service_charge'] ?? 0;
        $subtotal += (float)$data['equipment_charge'] ?? 0;
        $subtotal += (float)$data['ot_charge'] ?? 0;
        $subtotal += (float)$data['lab_tech_charge'] ?? 0;
        $subtotal += (float)$data['ambulance_charge'] ?? 0;
        $subtotal += (float)$data['food_charge'] ?? 0;
        foreach ($request->input('other_charges', []) as $oc) {
            $subtotal += (float)($oc['amount'] ?? 0);
        }
        $data['subtotal'] = $subtotal;
        if (($data['discount_type'] ?? '') === 'percentage') {
            $data['discount_amount'] = ($subtotal * ((float)$data['discount'] ?? 0)) / 100;
        } else {
            $data['discount_amount'] = (float)$data['discount'] ?? 0;
        }
        $data['total'] = $subtotal - $data['discount_amount'];
        $data['due'] = $data['total'] - ((float)$data['paid'] ?? 0);
        $ipdBilling->update($data);
        if (count($ipdBilling->attechments) > 0) {
            foreach ($ipdBilling->attechments as $media) {
                if (! in_array($media->file_name, $request->input('attechments', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ipdBilling->attechments->pluck('file_name')->toArray();
        foreach ($request->input('attechments', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ipdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
            }
        }
        return redirect()->route('admin.ipd-billings.index');
    }

    public function show(IpdBilling $ipdBilling)
    {
        abort_if(Gate::denies('ipd_billing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBilling->load('ipd', 'created_by');

        return view('admin.ipdBillings.show', compact('ipdBilling'));
    }

    public function destroy(IpdBilling $ipdBilling)
    {
        abort_if(Gate::denies('ipd_billing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBilling->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdBillingRequest $request)
    {
        $ipdBillings = IpdBilling::find(request('ids'));

        foreach ($ipdBillings as $ipdBilling) {
            $ipdBilling->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_billing_create') && Gate::denies('ipd_billing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdBilling();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
