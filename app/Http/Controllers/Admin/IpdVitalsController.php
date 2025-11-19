<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdVitalRequest;
use App\Http\Requests\StoreIpdVitalRequest;
use App\Http\Requests\UpdateIpdVitalRequest;
use App\Models\IpdAdmission;
use App\Models\IpdVital;
use App\Models\IpdMedication;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdVitalsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_vital_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdVitals = IpdVital::with(['ipd', 'created_by', 'media'])->get();

        return view('admin.ipdVitals.index', compact('ipdVitals'));
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

        $room = DB::table('ipd_rooms')->select('room_no', 'ward_type')->where('id', $ipd->room_id)->first();
        $bed = DB::table('ipd_beds')->select('bed_no', 'charges_per_day')->where('id', $ipd->bed_id)->first();

        return response()->json([
            'ipd' => $ipd,
            'patient' => $patient,
            'doctor' => $doctor,
            'room' => $room,
            'bed' => $bed,
            'medication' => $medication
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_vital_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdVitals.create', compact('ipds'));
    }

    public function store(StoreIpdVitalRequest $request)
    {
        $ipdVital = IpdVital::create($request->all());

        if ($request->input('attechments', false)) {
            $ipdVital->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdVital->id]);
        }

        return redirect()->route('admin.ipd-vitals.index');
    }

    public function edit(IpdVital $ipdVital)
    {
        abort_if(Gate::denies('ipd_vital_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdVital->load('ipd', 'created_by');

        return view('admin.ipdVitals.edit', compact('ipdVital', 'ipds'));
    }

    public function update(UpdateIpdVitalRequest $request, IpdVital $ipdVital)
    {
        $ipdVital->update($request->all());

        if ($request->input('attechments', false)) {
            if (! $ipdVital->attechments || $request->input('attechments') !== $ipdVital->attechments->file_name) {
                if ($ipdVital->attechments) {
                    $ipdVital->attechments->delete();
                }
                $ipdVital->addMedia(storage_path('tmp/uploads/' . basename($request->input('attechments'))))->toMediaCollection('attechments');
            }
        } elseif ($ipdVital->attechments) {
            $ipdVital->attechments->delete();
        }

        return redirect()->route('admin.ipd-vitals.index');
    }

    public function show(IpdVital $ipdVital)
    {
        abort_if(Gate::denies('ipd_vital_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdVital->load('ipd', 'created_by');

        return view('admin.ipdVitals.show', compact('ipdVital'));
    }

    public function destroy(IpdVital $ipdVital)
    {
        abort_if(Gate::denies('ipd_vital_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdVital->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdVitalRequest $request)
    {
        $ipdVitals = IpdVital::find(request('ids'));

        foreach ($ipdVitals as $ipdVital) {
            $ipdVital->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_vital_create') && Gate::denies('ipd_vital_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdVital();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
