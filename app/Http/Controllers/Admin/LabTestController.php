<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyLabTestRequest;
use App\Http\Requests\StoreLabTestRequest;
use App\Http\Requests\UpdateLabTestRequest;
use App\Models\LabTest;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class LabTestController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('lab_test_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $labTests = LabTest::with(['created_by'])->get();

        return view('admin.labTests.index', compact('labTests'));
    }

    public function create()
    {
        abort_if(Gate::denies('lab_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.labTests.create');
    }

    public function store(StoreLabTestRequest $request)
    {
        $labTest = LabTest::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $labTest->id]);
        }

        return redirect()->route('admin.lab-tests.index');
    }

    public function edit(LabTest $labTest)
    {
        abort_if(Gate::denies('lab_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $labTest->load('created_by');

        return view('admin.labTests.edit', compact('labTest'));
    }

    public function update(UpdateLabTestRequest $request, LabTest $labTest)
    {
        $labTest->update($request->all());

        return redirect()->route('admin.lab-tests.index');
    }

    public function show(LabTest $labTest)
    {
        abort_if(Gate::denies('lab_test_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $labTest->load('created_by', 'testOpdTests');

        return view('admin.labTests.show', compact('labTest'));
    }

    public function destroy(LabTest $labTest)
    {
        abort_if(Gate::denies('lab_test_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $labTest->delete();

        return back();
    }

    public function massDestroy(MassDestroyLabTestRequest $request)
    {
        $labTests = LabTest::find(request('ids'));

        foreach ($labTests as $labTest) {
            $labTest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('lab_test_create') && Gate::denies('lab_test_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new LabTest();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
