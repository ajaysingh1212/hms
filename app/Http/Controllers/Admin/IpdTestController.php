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
use Gate;
use Illuminate\Http\Request;
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
