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

    public function create()
    {
        abort_if(Gate::denies('opd_test_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('visit_date', 'id')->prepend(trans('global.pleaseSelect'), '');

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
