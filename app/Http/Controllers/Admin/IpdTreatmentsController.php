<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdTreatmentRequest;
use App\Http\Requests\StoreIpdTreatmentRequest;
use App\Http\Requests\UpdateIpdTreatmentRequest;
use App\Models\IpdAdmission;
use App\Models\IpdBed;
use App\Models\IpdTreatment;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdTreatmentsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_treatment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTreatments = IpdTreatment::with(['ipd', 'created_by', 'media'])->get();

        return view('admin.ipdTreatments.index', compact('ipdTreatments'));
    }
public function getIpdDetails(Request $request)
{
    try {

        $ipd_id = $request->ipd_id;

        // ---- 1. IPD ----
        $ipd = DB::table('ipd_admissions')->where('id', $ipd_id)->first();

        if (!$ipd) {
            return response()->json(['error' => true, 'message' => 'IPD Not Found']);
        }

        // ---- 2. PATIENT ----
        $patient = DB::table('appointments')
            ->leftJoin('department_names', 'appointments.department_id', '=', 'department_names.id')
            ->select(
                'appointments.*',
                'department_names.name as department_name'
            )
            ->where('appointments.id', $ipd->patient_id)
            ->first();

        if(!$patient){
            $patient = (object)[
                'patient_name' => null,
                'mobile_number' => null,
                'reason_for_visit' => null,
                'department_name' => null
            ];
        }

        // ---- 3. DOCTOR ----
        $doctor = DB::table('add_doctors')
            ->leftJoin('department_names', 'add_doctors.select_department_id', '=', 'department_names.id')
            ->select(
                'add_doctors.*',
                'department_names.name as doctor_department'
            )
            ->where('add_doctors.id', $ipd->doctor_id)
            ->first();

        if(!$doctor){
            $doctor = (object)[
                'doctor_name' => null,
                'doctor_department' => null,
                'doctor_fee' => null,
                'qualifications' => null,
                'experience' => null,
                'phone' => null,
                'available_days' => []
            ];
        } else {
            $doctor->available_days = $doctor->available_days
                ? json_decode($doctor->available_days, true)
                : [];
        }

        // ---- 4. ROOM ----
        $room = DB::table('ipd_rooms')->where('id', $ipd->room_id)->first();
        if(!$room){
            $room = (object)[
                'room_no' => null,
                'ward_type' => null,
                'charges_per_day' => null
            ];
        }

        // ---- 5. CURRENT BED ----
        $current_bed = DB::table('ipd_beds')->where('id', $ipd->bed_id)->first();
        if(!$current_bed){
            $current_bed = (object)[
                'bed_no' => null,
                'charges_per_day' => null,
                'status' => null
            ];
        }

        // ---- 6. ALL BEDS ----
        $beds = DB::table('ipd_beds')
            ->where('room_id', $ipd->room_id)
            ->orderBy('bed_no')
            ->get();

        return response()->json([
            'ipd'         => $ipd,
            'patient'     => $patient,
            'doctor'      => $doctor,
            'room'        => $room,
            'current_bed' => $current_bed,
            'beds'        => $beds
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'error' => true,
            'msg' => $e->getMessage(),
            'line' => $e->getLine()
        ], 500);
    }
}


    public function create()
    {
        abort_if(Gate::denies('ipd_treatment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdTreatments.create', compact('ipds'));
    }

public function store(StoreIpdTreatmentRequest $request)
{
    // Create IPD Treatment
    $ipdTreatment = IpdTreatment::create($request->all());

    // Handle Dropzone attechment files (multiple)
    if ($request->input('attechment', false)) {
        foreach ($request->input('attechment') as $file) {
            if (!empty($file)) {
                $ipdTreatment
                    ->addMedia(storage_path('tmp/uploads/' . basename($file)))
                    ->toMediaCollection('attechment');
            }
        }
    }

    // Handle CKEditor uploaded images
    if ($media = $request->input('ck-media', false)) {
        Media::whereIn('id', $media)->update(['model_id' => $ipdTreatment->id]);
    }

    return redirect()->route('admin.ipd-treatments.index')
        ->with('message', 'IPD Treatment created successfully.');
}

    public function edit(IpdTreatment $ipdTreatment)
    {
        abort_if(Gate::denies('ipd_treatment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdTreatment->load('ipd', 'created_by');

        return view('admin.ipdTreatments.edit', compact('ipdTreatment', 'ipds'));
    }

    public function update(UpdateIpdTreatmentRequest $request, IpdTreatment $ipdTreatment)
    {
        $ipdTreatment->update($request->all());

        if ($request->input('attechment', false)) {
            if (! $ipdTreatment->attechment || $request->input('attechment') !== $ipdTreatment->attechment->file_name) {
                if ($ipdTreatment->attechment) {
                    $ipdTreatment->attechment->delete();
                }
                $ipdTreatment->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechment'))))->toMediaCollection('attechment');
            }
        } elseif ($ipdTreatment->attechment) {
            $ipdTreatment->attechment->delete();
        }

        return redirect()->route('admin.ipd-treatments.index');
    }

    public function show(IpdTreatment $ipdTreatment)
    {
        abort_if(Gate::denies('ipd_treatment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTreatment->load('ipd', 'created_by');

        return view('admin.ipdTreatments.show', compact('ipdTreatment'));
    }

    public function destroy(IpdTreatment $ipdTreatment)
    {
        abort_if(Gate::denies('ipd_treatment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTreatment->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdTreatmentRequest $request)
    {
        $ipdTreatments = IpdTreatment::find(request('ids'));

        foreach ($ipdTreatments as $ipdTreatment) {
            $ipdTreatment->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_treatment_create') && Gate::denies('ipd_treatment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdTreatment();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

}
