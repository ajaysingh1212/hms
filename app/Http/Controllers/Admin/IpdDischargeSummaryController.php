<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdDischargeSummaryRequest;
use App\Http\Requests\StoreIpdDischargeSummaryRequest;
use App\Http\Requests\UpdateIpdDischargeSummaryRequest;
use App\Models\IpdAdmission;
use App\Models\IpdBilling;
use App\Models\IpdDischargeSummary;
use App\Models\IpdMedication;
use App\Models\IpdTest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdDischargeSummaryController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_discharge_summary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdDischargeSummaries = IpdDischargeSummary::with(['ipd', 'created_by', 'media'])->get();

        return view('admin.ipdDischargeSummaries.index', compact('ipdDischargeSummaries'));
    }
public function getFullSummary(Request $request)
{
    $ipdId = $request->ipd_id;

    $ipd = IpdAdmission::with([
        'patient',
        'doctor',
        'bed.room'
    ])->findOrFail($ipdId);

    /* ------------------------------
        1. DOCTOR FEE × DAYS
    --------------------------------*/
    $days = now()->diffInDays($ipd->admission_date) + 1;
    $doctorFee = $ipd->doctor->doctor_fee ?? 0;
    $doctorCharge = $doctorFee * $days;

    /* ------------------------------
        2. BED CHARGES × DAYS
    --------------------------------*/
    $bedCharge = ($ipd->bed->charges_per_day ?? 0) * $days;

    /* ------------------------------
        3. MEDICINE CHARGES (all medicines)
    --------------------------------*/
    $medications = IpdMedication::with('medicines')
        ->where('ipd_id', $ipdId)->get();

    $medicineList = [];
    $medicineTotal = 0;

    foreach ($medications as $m) {
        foreach ($m->medicines as $med) {
            $medicineList[] = [
                'name' => $med->name,
                'price' => $med->price,
            ];
            $medicineTotal += $med->price;
        }
    }

    /* ------------------------------
        4. TEST CHARGES
    --------------------------------*/
    $tests = IpdTest::with('tests')
        ->where('ipd_id', $ipdId)->get();

    $testList = [];
    $testTotal = 0;

    foreach ($tests as $t) {
        foreach ($t->tests as $single) {
            $testList[] = [
                'name' => $single->test_name,
                'price' => $single->price,
            ];
            $testTotal += $single->price;
        }
    }

    /* ------------------------------
        5. ALL IPD BILLING RECORDS
    --------------------------------*/
    $billing = IpdBilling::where('ipd_id', $ipdId)->get();

    $totalBilled = $billing->sum('total');
    $totalPaid   = $billing->sum('paid');
    $totalDue    = $totalBilled - $totalPaid;

    return response()->json([
        'ipd' => $ipd,
        'days' => $days,

        'doctor_charge' => $doctorCharge,
        'bed_charge'    => $bedCharge,

        'medicine_list' => $medicineList,
        'medicine_total' => $medicineTotal,

        'test_list' => $testList,
        'test_total' => $testTotal,

        'billing_history' => $billing,
        'total_billed' => $totalBilled,
        'total_paid' => $totalPaid,
        'total_due' => $totalDue,
    ]);
}


    public function create()
    {
        abort_if(Gate::denies('ipd_discharge_summary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdDischargeSummaries.create', compact('ipds'));
    }

    public function store(StoreIpdDischargeSummaryRequest $request)
    {
        $ipdDischargeSummary = IpdDischargeSummary::create($request->all());

        if ($request->input('attechments', false)) {
            $ipdDischargeSummary->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdDischargeSummary->id]);
        }

        return redirect()->route('admin.ipd-discharge-summaries.index');
    }

    public function edit(IpdDischargeSummary $ipdDischargeSummary)
    {
        abort_if(Gate::denies('ipd_discharge_summary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdDischargeSummary->load('ipd', 'created_by');

        return view('admin.ipdDischargeSummaries.edit', compact('ipdDischargeSummary', 'ipds'));
    }

    public function update(UpdateIpdDischargeSummaryRequest $request, IpdDischargeSummary $ipdDischargeSummary)
    {
        $ipdDischargeSummary->update($request->all());

        if ($request->input('attechments', false)) {
            if (! $ipdDischargeSummary->attechments || $request->input('attechments') !== $ipdDischargeSummary->attechments->file_name) {
                if ($ipdDischargeSummary->attechments) {
                    $ipdDischargeSummary->attechments->delete();
                }
                $ipdDischargeSummary->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
            }
        } elseif ($ipdDischargeSummary->attechments) {
            $ipdDischargeSummary->attechments->delete();
        }

        return redirect()->route('admin.ipd-discharge-summaries.index');
    }

    public function show(IpdDischargeSummary $ipdDischargeSummary)
    {
        abort_if(Gate::denies('ipd_discharge_summary_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdDischargeSummary->load('ipd', 'created_by');

        return view('admin.ipdDischargeSummaries.show', compact('ipdDischargeSummary'));
    }

    public function destroy(IpdDischargeSummary $ipdDischargeSummary)
    {
        abort_if(Gate::denies('ipd_discharge_summary_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdDischargeSummary->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdDischargeSummaryRequest $request)
    {
        $ipdDischargeSummaries = IpdDischargeSummary::find(request('ids'));

        foreach ($ipdDischargeSummaries as $ipdDischargeSummary) {
            $ipdDischargeSummary->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_discharge_summary_create') && Gate::denies('ipd_discharge_summary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdDischargeSummary();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
