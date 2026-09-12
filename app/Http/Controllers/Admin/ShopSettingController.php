<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyShopSettingRequest;
use App\Http\Requests\StoreShopSettingRequest;
use App\Http\Requests\UpdateShopSettingRequest;
use App\Models\ShopSetting;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ShopSettingController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('shop_setting_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopSettings = ShopSetting::with(['created_by', 'media'])->get();

        return view('admin.shopSettings.index', compact('shopSettings'));
    }

    public function create()
    {
        abort_if(Gate::denies('shop_setting_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.shopSettings.create');
    }

    public function store(StoreShopSettingRequest $request)
    {
        $shopSetting = ShopSetting::create($request->all());

        foreach ($request->input('logo', []) as $file) {
            $shopSetting->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('logo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $shopSetting->id]);
        }

        return redirect()->route('admin.shop-settings.index');
    }

    public function edit(ShopSetting $shopSetting)
    {
        abort_if(Gate::denies('shop_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopSetting->load('created_by');

        return view('admin.shopSettings.edit', compact('shopSetting'));
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

        return redirect()->route('admin.shop-settings.index');
    }

    public function show(ShopSetting $shopSetting)
    {
        abort_if(Gate::denies('shop_setting_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopSetting->load('created_by');

        return view('admin.shopSettings.show', compact('shopSetting'));
    }

    public function destroy(ShopSetting $shopSetting)
    {
        abort_if(Gate::denies('shop_setting_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $shopSetting->delete();

        return back();
    }

    public function massDestroy(MassDestroyShopSettingRequest $request)
    {
        $shopSettings = ShopSetting::find(request('ids'));

        foreach ($shopSettings as $shopSetting) {
            $shopSetting->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('shop_setting_create') && Gate::denies('shop_setting_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ShopSetting();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
