<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreShopSettingRequest;
use App\Http\Requests\UpdateShopSettingRequest;
use App\Http\Resources\Admin\ShopSettingResource;
use App\Models\ShopSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopSettingApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('shop_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ShopSettingResource(ShopSetting::with(['created_by'])->get());
    }

    public function store(StoreShopSettingRequest $request)
    {
        $shopSetting = ShopSetting::create($request->all());

        foreach ($request->input('logo', []) as $file) {
            $shopSetting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('logo');
        }

        return (new ShopSettingResource($shopSetting))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ShopSetting $shopSetting)
    {
        abort_if(Gate::denies('shop_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ShopSettingResource($shopSetting->load(['created_by']));
    }

    public function update(UpdateShopSettingRequest $request, ShopSetting $shopSetting)
    {
        $shopSetting->update($request->all());

        if (count($shopSetting->logo) > 0) {
            foreach ($shopSetting->logo as $media) {
                if (! in_array($media->file_name, $request->input('logo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $shopSetting->logo->pluck('file_name')->toArray();
        foreach ($request->input('logo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $shopSetting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('logo');
            }
        }

        return (new ShopSettingResource($shopSetting))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(ShopSetting $shopSetting)
    {
        abort_if(Gate::denies('shop_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopSetting->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
