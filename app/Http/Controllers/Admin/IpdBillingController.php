<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyIpdBillingRequest;
use App\Http\Requests\StoreIpdBillingRequest;
use App\Http\Requests\UpdateIpdBillingRequest;
use App\Models\IpdAdmission;
use App\Models\IpdBilling;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class IpdBillingController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('ipd_billing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBillings = IpdBilling::with(['ipd', 'created_by', 'media'])->get();

        return view('admin.ipdBillings.index', compact('ipdBillings'));
    }

    public function create()
    {
        abort_if(Gate::denies('ipd_billing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ipdBillings.create', compact('ipds'));
    }

    public function store(StoreIpdBillingRequest $request)
    {
        $ipdBilling = IpdBilling::create($request->all());

        foreach ($request->input('attechments', []) as $file) {
            $ipdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $ipdBilling->id]);
        }

        return redirect()->route('admin.ipd-billings.index');
    }

    public function edit(IpdBilling $ipdBilling)
    {
        abort_if(Gate::denies('ipd_billing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipds = IpdAdmission::pluck('ipd_number', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ipdBilling->load('ipd', 'created_by');

        return view('admin.ipdBillings.edit', compact('ipdBilling', 'ipds'));
    }

    public function update(UpdateIpdBillingRequest $request, IpdBilling $ipdBilling)
    {
        $ipdBilling->update($request->all());

        if (count($ipdBilling->attechments) > 0) {
            foreach ($ipdBilling->attechments as $media) {
                if (! in_array($media->file_name, $request->input('attechments', []))) {
                    $media->delete();
                }
            }
        }
        $media = $ipdBilling->attechments->pluck('file_name')->toArray();
        foreach ($request->input('attechments', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $ipdBilling->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attechments');
            }
        }

        return redirect()->route('admin.ipd-billings.index');
    }

    public function show(IpdBilling $ipdBilling)
    {
        abort_if(Gate::denies('ipd_billing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBilling->load('ipd', 'created_by');

        return view('admin.ipdBillings.show', compact('ipdBilling'));
    }

    public function destroy(IpdBilling $ipdBilling)
    {
        abort_if(Gate::denies('ipd_billing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ipdBilling->delete();

        return back();
    }

    public function massDestroy(MassDestroyIpdBillingRequest $request)
    {
        $ipdBillings = IpdBilling::find(request('ids'));

        foreach ($ipdBillings as $ipdBilling) {
            $ipdBilling->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('ipd_billing_create') && Gate::denies('ipd_billing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new IpdBilling();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
