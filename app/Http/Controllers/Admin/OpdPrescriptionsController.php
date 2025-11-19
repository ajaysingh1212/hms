<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOpdPrescriptionRequest;
use App\Http\Requests\StoreOpdPrescriptionRequest;
use App\Http\Requests\UpdateOpdPrescriptionRequest;
use App\Models\Medicine;
use App\Models\OpdPrescription;
use App\Models\OpdVisit;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OpdPrescriptionsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_prescription_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdPrescriptions = OpdPrescription::with(['opd', 'medicines', 'created_by', 'media'])->get();

        return view('admin.opdPrescriptions.index', compact('opdPrescriptions'));
    }

    /* ------------------------------------------------------------
        AJAX: Fetch doctor / patient / opd visit details
    -------------------------------------------------------------*/
    public function opdDetails(Request $request)
    {
        $opdId = $request->query('opd_id');

        if (!$opdId) {
            return response()->json(['error' => 'opd_id required'], 422);
        }

        $opd = OpdVisit::with(['doctor', 'patient', 'created_by'])->find($opdId);

        if (!$opd) {
            return response()->json(['error' => 'OPD not found'], 404);
        }

        $doctor = $opd->doctor;
        $doctor_available_days = [];

        if ($doctor) {
            $doctor_available_days = is_array($doctor->available_days)
                ? $doctor->available_days
                : json_decode($doctor->available_days, true);
        }

        // strip_tags
        if ($doctor) {
            $doctor->doctor_name = strip_tags($doctor->doctor_name);
            $doctor->phone = strip_tags($doctor->phone);
            $doctor->phone_alt = strip_tags($doctor->phone_alt);
            $doctor->experience = strip_tags($doctor->experience);
            $doctor->description = strip_tags($doctor->description);
        }

        $patient = $opd->patient;

        if ($patient) {
            $patient->patient_name = strip_tags($patient->patient_name);
            $patient->mobile_number = strip_tags($patient->mobile_number);
            $patient->reason_for_visit = strip_tags($patient->reason_for_visit);
            $patient->patient_number = strip_tags($patient->patient_number);
        }

        $opd->symptoms = strip_tags($opd->symptoms);
        $opd->diagnosis = strip_tags($opd->diagnosis);
        $opd->notes = strip_tags($opd->notes);
        $opd->visit_type = strip_tags($opd->visit_type);
        $opd->status = strip_tags($opd->status);

        return response()->json([
            'opd'                   => $opd,
            'doctor'                => $doctor,
            'doctor_available_days' => $doctor_available_days,
            'patient'               => $patient,
        ]);
    }

    /* ------------------------------------------------------------
        CREATE
    -------------------------------------------------------------*/
    public function create()
    {
        abort_if(Gate::denies('opd_prescription_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('opd_id', 'id')->prepend('Please Select', '');

        $medicines = Medicine::pluck('name', 'id');

        return view('admin.opdPrescriptions.create', compact('medicines', 'opds'));
    }

    public function store(StoreOpdPrescriptionRequest $request)
    {
        $opdPrescription = OpdPrescription::create($request->all());
        $opdPrescription->medicines()->sync($request->input('medicines', []));

        foreach ($request->input('attechment', []) as $file) {
            $opdPrescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $opdPrescription->id]);
        }

        return redirect()->route('admin.opd-prescriptions.index');
    }

    /* ------------------------------------------------------------
        EDIT
    -------------------------------------------------------------*/
    public function edit(OpdPrescription $opdPrescription)
    {
        abort_if(Gate::denies('opd_prescription_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('opd_id', 'id')->prepend('Please Select', '');

        $medicines = Medicine::pluck('name', 'id');

        $opdPrescription->load('opd', 'medicines', 'created_by');

        return view('admin.opdPrescriptions.edit', compact('medicines', 'opdPrescription', 'opds'));
    }

    public function update(UpdateOpdPrescriptionRequest $request, OpdPrescription $opdPrescription)
    {
        $opdPrescription->update($request->all());
        $opdPrescription->medicines()->sync($request->input('medicines', []));

        // DELETE removed media
        if (count($opdPrescription->attechment) > 0) {
            foreach ($opdPrescription->attechment as $media) {
                if (!in_array($media->file_name, $request->input('attechment', []))) {
                    $media->delete();
                }
            }
        }

        // ADD new media
        $existingMedia = $opdPrescription->attechment->pluck('file_name')->toArray();
        foreach ($request->input('attechment', []) as $file) {
            if (!in_array($file, $existingMedia)) {
                $opdPrescription->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
            }
        }

        return redirect()->route('admin.opd-prescriptions.index');
    }

    /* ------------------------------------------------------------
        SHOW
    -------------------------------------------------------------*/
    public function show(OpdPrescription $opdPrescription)
    {
        abort_if(Gate::denies('opd_prescription_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdPrescription->load('opd', 'medicines', 'created_by');

        return view('admin.opdPrescriptions.show', compact('opdPrescription'));
    }

    /* ------------------------------------------------------------
        DELETE
    -------------------------------------------------------------*/
    public function destroy(OpdPrescription $opdPrescription)
    {
        abort_if(Gate::denies('opd_prescription_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdPrescription->delete();

        return back();
    }

    public function massDestroy(MassDestroyOpdPrescriptionRequest $request)
    {
        $items = OpdPrescription::find(request('ids'));

        foreach ($items as $item) {
            $item->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /* ------------------------------------------------------------
        CKEditor Upload
    -------------------------------------------------------------*/
    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('opd_prescription_create') && Gate::denies('opd_prescription_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OpdPrescription();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
