<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdTestRequest;
use App\Http\Requests\StoreIpdTestRequest;
use App\Http\Requests\UpdateIpdTestRequest;
use App\Models\IpdAdmission;
use App\Models\IpdTest;
use App\Models\LabTest;
use App\Models\IpdMedication;
use App\Models\IpdVital;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdTestController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTests = IpdTest::with(['ipd', 'tests', 'created_by', 'media'])->get();

        return view('admin.ipdTests.index', compact('ipdTests'));
    }

    public function getIpdFullDetails(Request $request)
    {
        $ipd_id = $request->ipd_id;

        $ipd = DB::table('ipd_admissions')->where('id', $ipd_id)->first();
        if (!$ipd) {
            return response()->json(['error' => true]);
        }

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

        $doctor = DB::table('add_doctors')
            ->leftJoin('department_names', 'add_doctors.select_department_id', '=', 'department_names.id')
            ->select(
                'add_doctors.doctor_name',
                'add_doctors.doctor_fee',
                'add_doctors.experience',
                'add_doctors.qualifications',
                'add_doctors.phone',
                'add_doctors.available_days',
                'department_names.name as doctor_department'
            )
            ->where('add_doctors.id', $ipd->doctor_id)
            ->first();

        if ($doctor && $doctor->available_days) {
            $doctor->available_days = json_decode($doctor->available_days, true);
        } else {
            $doctor->available_days = [];
        }

        $latestMedication = IpdMedication::where('ipd_id', $ipd_id)->with('medicines')->orderBy('id', 'DESC')->first();

        if ($latestMedication) {
            $meds = $latestMedication->medicines->map(function($m){
                return ['id' => $m->id, 'name' => $m->name];
            })->toArray();
            $attachments = DB::table('media')->where('model_id', $latestMedication->id)->where('model_type', 'App\\Models\\IpdMedication')->pluck('file_name');
            $medication = [
                'id' => $latestMedication->id,
                'medicines' => $meds,
                'dosage' => $latestMedication->dosage,
                'frequency' => $latestMedication->frequency,
                'route' => $latestMedication->route,
                'route_label' => isset(IpdMedication::ROUTE_SELECT[$latestMedication->route]) ? IpdMedication::ROUTE_SELECT[$latestMedication->route] : $latestMedication->route,
                'notes' => $latestMedication->notes,
                'attachments' => $attachments,
                'created_at' => $latestMedication->created_at,
            ];
        } else {
            $medication = [
                'id' => null,
                'medicines' => [],
                'dosage' => null,
                'frequency' => null,
                'route' => null,
                'route_label' => null,
                'notes' => null,
                'attachments' => [],
                'created_at' => null,
            ];
        }

        $vitals = IpdVital::where('ipd_id', $ipd_id)->orderBy('id', 'DESC')->get()->map(function($v){
            return [
                'id' => $v->id,
                'date_time' => $v->date_time,
                'temperature' => $v->temperature,
                'pulse' => $v->pulse,
                'bp' => $v->bp,
                'spo_2' => $v->spo_2,
                'respiration' => $v->respiration,
                'notes' => $v->notes,
                'attachments' => $v->getMedia('attechments')->map(function($m){ return $m->file_name; })->toArray()
            ];
        })->toArray();

        $room = DB::table('ipd_rooms')->select('room_no', 'ward_type')->where('id', $ipd->room_id)->first();
        $bed = DB::table('ipd_beds')->select('bed_no', 'charges_per_day')->where('id', $ipd->bed_id)->first();

        return response()->json([
            'ipd' => $ipd,
            'patient' => $patient,
            'doctor' => $doctor,
            'room' => $room,
            'bed' => $bed,
            'medication' => $medication,
            'vitals' => $vitals
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tests = LabTest::pluck('test_name', 'id');

        return view('admin.ipdTests.create', compact('ipds', 'tests'));
    }

    public function store(StoreIpdTestRequest $request)
    {
        $ipdTest = IpdTest::create($request->all());
        $ipdTest->tests()->sync($request->input('tests', []));
        foreach ($request->input('attechments', []) as $file) {
            $ipdTest->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdTest->id]);
        }

        return redirect()->route('admin.ipd-tests.index');
    }

    public function edit(IpdTest $ipdTest)
    {
        abort_if(Gate::denies('ipd_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tests = LabTest::pluck('test_name', 'id');

        $ipdTest->load('ipd', 'tests', 'created_by');

        return view('admin.ipdTests.edit', compact('ipdTest', 'ipds', 'tests'));
    }

    public function update(UpdateIpdTestRequest $request, IpdTest $ipdTest)
    {
        $ipdTest->update($request->all());
        $ipdTest->tests()->sync($request->input('tests', []));
        if (count($ipdTest->attechments) > 0) {
            foreach ($ipdTest->attechments as $media) {
                if (! in_array($media->file_name, $request->input('attechments', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ipdTest->attechments->pluck('file_name')->toArray();
        foreach ($request->input('attechments', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ipdTest->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
            }
        }

        return redirect()->route('admin.ipd-tests.index');
    }

    public function show(IpdTest $ipdTest)
    {
        abort_if(Gate::denies('ipd_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTest->load('ipd', 'tests', 'created_by');

        return view('admin.ipdTests.show', compact('ipdTest'));
    }

    public function destroy(IpdTest $ipdTest)
    {
        abort_if(Gate::denies('ipd_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdTest->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdTestRequest $request)
    {
        $ipdTests = IpdTest::find(request('ids'));

        foreach ($ipdTests as $ipdTest) {
            $ipdTest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_test_create') && Gate::denies('ipd_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdTest();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
