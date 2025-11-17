<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyDepartmentNameRequest;
use App\Http\Requests\StoreDepartmentNameRequest;
use App\Http\Requests\UpdateDepartmentNameRequest;
use App\Models\DepartmentName;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class DepartmentNameController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('department_name_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departmentNames = DepartmentName::with(['created_by'])->get();

        return view('admin.departmentNames.index', compact('departmentNames'));
    }

    public function create()
    {
        abort_if(Gate::denies('department_name_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.departmentNames.create');
    }

    public function store(StoreDepartmentNameRequest $request)
    {
        $departmentName = DepartmentName::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $departmentName->id]);
        }

        return redirect()->route('admin.department-names.index');
    }

    public function edit(DepartmentName $departmentName)
    {
        abort_if(Gate::denies('department_name_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departmentName->load('created_by');

        return view('admin.departmentNames.edit', compact('departmentName'));
    }

    public function update(UpdateDepartmentNameRequest $request, DepartmentName $departmentName)
    {
        $departmentName->update($request->all());

        return redirect()->route('admin.department-names.index');
    }

    public function show(DepartmentName $departmentName)
    {
        abort_if(Gate::denies('department_name_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departmentName->load('created_by', 'selectDepartmentAddDoctors');

        return view('admin.departmentNames.show', compact('departmentName'));
    }

    public function destroy(DepartmentName $departmentName)
    {
        abort_if(Gate::denies('department_name_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departmentName->delete();

        return back();
    }

    public function massDestroy(MassDestroyDepartmentNameRequest $request)
    {
        $departmentNames = DepartmentName::find(request('ids'));

        foreach ($departmentNames as $departmentName) {
            $departmentName->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('department_name_create') && Gate::denies('department_name_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new DepartmentName();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
