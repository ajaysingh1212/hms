<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAddDoctorRequest;
use App\Http\Requests\StoreAddDoctorRequest;
use App\Http\Requests\UpdateAddDoctorRequest;
use App\Models\AddDoctor;
use App\Models\DepartmentName;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AddDoctorsController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('add_doctor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addDoctors = AddDoctor::with(['select_department', 'created_by'])->get();

        return view('admin.addDoctors.index', compact('addDoctors'));
    }

    public function create()
    {
        abort_if(Gate::denies('add_doctor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_departments = DepartmentName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.addDoctors.create', compact('select_departments'));
    }

    public function store(StoreAddDoctorRequest $request)
    {
        
        $user = User::create([
            'name'     => $request->login_name,
            'email'    => $request->login_email,
            'password' => $request->login_password, 
        ]);

        
        $doctorRoleId = 3; 
        $user->roles()->sync($doctorRoleId);

       
        $addDoctor = AddDoctor::create([
            'select_department_id'      => $request->select_department_id,
            'doctor_name'               => $request->doctor_name,
            'available_days'            => json_encode($request->available_days), 
            'appointment_slot_duration' => $request->appointment_slot_duration,
            'max_patients_per_day'      => $request->max_patients_per_day,
            'doctor_fee'                => $request->doctor_fee,
            'description'               => $request->description,
            'created_by_id'             => $user->id, 
            'phone'                      => $request->phone,
            'phone_alt'                    => $request->phone_alt,
            'experience'                => $request->experience,
            'qualifications'            => $request->qualifications,
        ]);


       
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $addDoctor->id]);
        }

        return redirect()->route('admin.add-doctors.index')
            ->with('success', 'Doctor created successfully!');
    }


    public function edit(AddDoctor $addDoctor)
    {
        abort_if(Gate::denies('add_doctor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_departments = DepartmentName::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $addDoctor->load('select_department', 'created_by');

        return view('admin.addDoctors.edit', compact('addDoctor', 'select_departments'));
    }

    public function update(UpdateAddDoctorRequest $request, AddDoctor $addDoctor)
    {
        $addDoctor->update($request->all());

        return redirect()->route('admin.add-doctors.index');
    }

    public function show(AddDoctor $addDoctor)
    {
        abort_if(Gate::denies('add_doctor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addDoctor->load('select_department', 'created_by', 'doctorOpdVisits');

        return view('admin.addDoctors.show', compact('addDoctor'));
    }

    public function destroy(AddDoctor $addDoctor)
    {
        abort_if(Gate::denies('add_doctor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addDoctor->delete();

        return back();
    }

    public function massDestroy(MassDestroyAddDoctorRequest $request)
    {
        $addDoctors = AddDoctor::find(request('ids'));

        foreach ($addDoctors as $addDoctor) {
            $addDoctor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('add_doctor_create') && Gate::denies('add_doctor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AddDoctor();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
