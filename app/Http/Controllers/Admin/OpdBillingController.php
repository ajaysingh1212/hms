<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyOpdBillingRequest;
use App\Http\Requests\StoreOpdBillingRequest;
use App\Http\Requests\UpdateOpdBillingRequest;
use App\Models\OpdBilling;
use App\Models\OpdVisit;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class OpdBillingController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('opd_billing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdBillings = OpdBilling::with(['opd', 'created_by', 'media'])->get();

        return view('admin.opdBillings.index', compact('opdBillings'));
    }

    public function create()
    {
        abort_if(Gate::denies('opd_billing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('visit_date', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.opdBillings.create', compact('opds'));
    }

    public function store(StoreOpdBillingRequest $request)
    {
        $opdBilling = OpdBilling::create($request->all());

        foreach ($request->input('attechment', []) as $file) {
            $opdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $opdBilling->id]);
        }

        return redirect()->route('admin.opd-billings.index');
    }

    public function edit(OpdBilling $opdBilling)
    {
        abort_if(Gate::denies('opd_billing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opds = OpdVisit::pluck('visit_date', 'id')->prepend(trans('global.pleaseSelect'), '');

        $opdBilling->load('opd', 'created_by');

        return view('admin.opdBillings.edit', compact('opdBilling', 'opds'));
    }

    public function update(UpdateOpdBillingRequest $request, OpdBilling $opdBilling)
    {
        $opdBilling->update($request->all());

        if (count($opdBilling->attechment) > 0) {
            foreach ($opdBilling->attechment as $media) {
                if (! in_array($media->file_name, $request->input('attechment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $opdBilling->attechment->pluck('file_name')->toArray();
        foreach ($request->input('attechment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $opdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechment');
            }
        }

        return redirect()->route('admin.opd-billings.index');
    }

    public function show(OpdBilling $opdBilling)
    {
        abort_if(Gate::denies('opd_billing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdBilling->load('opd', 'created_by');

        return view('admin.opdBillings.show', compact('opdBilling'));
    }

    public function destroy(OpdBilling $opdBilling)
    {
        abort_if(Gate::denies('opd_billing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $opdBilling->delete();

        return back();
    }

    public function massDestroy(MassDestroyOpdBillingRequest $request)
    {
        $opdBillings = OpdBilling::find(request('ids'));

        foreach ($opdBillings as $opdBilling) {
            $opdBilling->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('opd_billing_create') && Gate::denies('opd_billing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new OpdBilling();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
