<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCarrierRequest;
use App\Http\Requests\UpdateCarrierRequest;
use App\Http\Resources\Admin\CarrierResource;
use App\Models\Carrier;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CarrierApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('carrier_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CarrierResource(Carrier::with(['created_by'])->get());
    }

    public function store(StoreCarrierRequest $request)
    {
        $carrier = Carrier::create($request->all());

        foreach ($request->input('brand_logo', []) as $file) {
            $carrier->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('brand_logo');
        }

        return (new CarrierResource($carrier))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Carrier $carrier)
    {
        abort_if(Gate::denies('carrier_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CarrierResource($carrier->load(['created_by']));
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

        return (new CarrierResource($carrier))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Carrier $carrier)
    {
        abort_if(Gate::denies('carrier_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $carrier->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
