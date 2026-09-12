<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCarrierRequest;
use App\Http\Requests\StoreCarrierRequest;
use App\Http\Requests\UpdateCarrierRequest;
use App\Models\Carrier;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CarrierController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('carrier_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $carriers = Carrier::with(['created_by', 'media'])->get();

        return view('admin.carriers.index', compact('carriers'));
    }

    public function create()
    {
        abort_if(Gate::denies('carrier_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.carriers.create');
    }

    public function store(StoreCarrierRequest $request)
    {
        $carrier = Carrier::create($request->all());

        foreach ($request->input('brand_logo', []) as $file) {
            $carrier->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('brand_logo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $carrier->id]);
        }

        return redirect()->route('admin.carriers.index');
    }

    public function edit(Carrier $carrier)
    {
        abort_if(Gate::denies('carrier_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $carrier->load('created_by');

        return view('admin.carriers.edit', compact('carrier'));
    }

    public function update(UpdateCarrierRequest $request, Carrier $carrier)
    {
        $carrier->update($request->all());

        if (count($carrier->brand_logo) > 0) {
            foreach ($carrier->brand_logo as $media) {
                if (! in_array($media->file_name, $request->input('brand_logo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $carrier->brand_logo->pluck('file_name')->toArray();
        foreach ($request->input('brand_logo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $carrier->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('brand_logo');
            }
        }

        return redirect()->route('admin.carriers.index');
    }

    public function show(Carrier $carrier)
    {
        abort_if(Gate::denies('carrier_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $carrier->load('created_by');

        return view('admin.carriers.show', compact('carrier'));
    }

    public function destroy(Carrier $carrier)
    {
        abort_if(Gate::denies('carrier_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $carrier->delete();

        return back();
    }

    public function massDestroy(MassDestroyCarrierRequest $request)
    {
        $carriers = Carrier::find(request('ids'));

        foreach ($carriers as $carrier) {
            $carrier->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('carrier_create') && Gate::denies('carrier_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Carrier();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
