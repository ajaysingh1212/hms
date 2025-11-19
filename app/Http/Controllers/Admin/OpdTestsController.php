<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOpdTestRequest;
use App\Http\Requests\StoreOpdTestRequest;
use App\Http\Requests\UpdateOpdTestRequest;
use App\Models\LabTest;
use App\Models\OpdTest;
use App\Models\OpdVisit;
use App\Models\OpdPrescription;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OpdTestsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdTests = OpdTest::with(['opd', 'tests', 'created_by'])->get();

        return view('admin.opdTests.index', compact('opdTests'));
    }

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

        // doctor available days array
        $doctor = $opd->doctor;
        $doctor_available_days = [];
        if ($doctor) {
            $doctor_available_days = is_array($doctor->available_days)
                ? $doctor->available_days
                : ( $doctor->available_days ? json_decode($doctor->available_days, true) : [] );
        }

        // sanitize doctor fields
        if ($doctor) {
            $doctor->doctor_name = strip_tags($doctor->doctor_name);
            $doctor->phone = strip_tags($doctor->phone);
            $doctor->phone_alt = strip_tags($doctor->phone_alt);
            $doctor->experience = strip_tags($doctor->experience);
            $doctor->description = strip_tags($doctor->description);
        }

        // patient sanitize
        $patient = $opd->patient;
        if ($patient) {
            $patient->patient_name = strip_tags($patient->patient_name);
            $patient->mobile_number = strip_tags($patient->mobile_number);
            $patient->reason_for_visit = strip_tags($patient->reason_for_visit);
            $patient->patient_number = strip_tags($patient->patient_number);
        }

        // opd sanitize
        $opd->symptoms = strip_tags($opd->symptoms);
        $opd->diagnosis = strip_tags($opd->diagnosis);
        $opd->notes = strip_tags($opd->notes);
        $opd->visit_type = strip_tags($opd->visit_type);
        $opd->status = strip_tags($opd->status);

        // Latest prescription for this OPD (if any)
        $latestPrescription = OpdPrescription::with('medicines')->where('opd_id', $opdId)->latest('created_at')->first();
        $prescriptionData = null;

        if ($latestPrescription) {
            // sanitize prescription fields
            $latestPrescription->dosage = strip_tags($latestPrescription->dosage);
            $latestPrescription->duration = strip_tags($latestPrescription->duration);
            $latestPrescription->instructions = strip_tags($latestPrescription->instructions);

            // medicines list with name + price
            $meds = [];
            foreach ($latestPrescription->medicines as $m) {
                $meds[] = [
                    'id'    => $m->id,
                    'name'  => strip_tags($m->name),
                    'price' => $m->price, // you confirmed column is `price`
                ];
            }

            $prescriptionData = [
                'id'         => $latestPrescription->id,
                'dosage'     => $latestPrescription->dosage,
                'duration'   => $latestPrescription->duration,
                'instructions' => $latestPrescription->instructions,
                'medicines'  => $meds
            ];
        }

        return response()->json([
            'opd'                   => $opd,
            'doctor'                => $doctor,
            'doctor_available_days' => $doctor_available_days,
            'patient'               => $patient,
            'prescription'          => $prescriptionData,
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('opd_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('opd_id', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tests = LabTest::pluck('test_name', 'id');

        return view('admin.opdTests.create', compact('opds', 'tests'));
    }

    public function store(StoreOpdTestRequest $request)
    {
        $opdTest = OpdTest::create($request->all());
        $opdTest->tests()->sync($request->input('tests', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $opdTest->id]);
        }

        return redirect()->route('admin.opd-tests.index');
    }

    public function edit(OpdTest $opdTest)
    {
        abort_if(Gate::denies('opd_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('visit_date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tests = LabTest::pluck('test_name', 'id');

        $opdTest->load('opd', 'tests', 'created_by');

        return view('admin.opdTests.edit', compact('opdTest', 'opds', 'tests'));
    }

    public function update(UpdateOpdTestRequest $request, OpdTest $opdTest)
    {
        $opdTest->update($request->all());
        $opdTest->tests()->sync($request->input('tests', []));

        return redirect()->route('admin.opd-tests.index');
    }

    public function show(OpdTest $opdTest)
    {
        abort_if(Gate::denies('opd_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdTest->load('opd', 'tests', 'created_by');

        return view('admin.opdTests.show', compact('opdTest'));
    }

    public function destroy(OpdTest $opdTest)
    {
        abort_if(Gate::denies('opd_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdTest->delete();

        return back();
    }

    public function massDestroy(MassDestroyOpdTestRequest $request)
    {
        $opdTests = OpdTest::find(request('ids'));

        foreach ($opdTests as $opdTest) {
            $opdTest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('opd_test_create') && Gate::denies('opd_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OpdTest();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
