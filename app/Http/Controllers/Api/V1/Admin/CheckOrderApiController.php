<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreCheckOrderRequest;
use App\Http\Requests\UpdateCheckOrderRequest;
use App\Http\Resources\Admin\CheckOrderResource;
use App\Models\CheckOrder;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOrderApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('check_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckOrderResource(CheckOrder::with(['select_user', 'select_products', 'created_by'])->get());
    }

    public function store(StoreCheckOrderRequest $request)
    {
        $checkOrder = CheckOrder::create($request->all());
        $checkOrder->select_products()->sync($request->input('select_products', []));
        foreach ($request->input('attachment', []) as $file) {
            $checkOrder->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
        }

        return (new CheckOrderResource($checkOrder))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CheckOrder $checkOrder)
    {
        abort_if(Gate::denies('check_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckOrderResource($checkOrder->load(['select_user', 'select_products', 'created_by']));
    }

    public function update(UpdateCheckOrderRequest $request, CheckOrder $checkOrder)
    {
        $checkOrder->update($request->all());
        $checkOrder->select_products()->sync($request->input('select_products', []));
        if (count($checkOrder->attachment) > 0) {
            foreach ($checkOrder->attachment as $media) {
                if (! in_array($media->file_name, $request->input('attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $checkOrder->attachment->pluck('file_name')->toArray();
        foreach ($request->input('attachment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $checkOrder->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
            }
        }

        return (new CheckOrderResource($checkOrder))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CheckOrder $checkOrder)
    {
        abort_if(Gate::denies('check_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkOrder->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
