<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCheckOrderRequest;
use App\Http\Requests\StoreCheckOrderRequest;
use App\Http\Requests\UpdateCheckOrderRequest;
use App\Models\CheckOrder;
use App\Models\Product;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CheckOrderController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('check_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkOrders = CheckOrder::with(['select_user', 'select_products', 'created_by', 'media'])->get();

        return view('frontend.checkOrders.index', compact('checkOrders'));
    }

    public function create()
    {
        abort_if(Gate::denies('check_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.checkOrders.create');
    }

    public function store(StoreCheckOrderRequest $request)
    {
        $checkOrder = CheckOrder::create($request->all());

        foreach ($request->input('attachment', []) as $file) {
            $checkOrder->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $checkOrder->id]);
        }

        return redirect()->route('frontend.check-orders.index');
    }

    public function edit(CheckOrder $checkOrder)
    {
        abort_if(Gate::denies('check_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $select_products = Product::pluck('name', 'id');

        $checkOrder->load('select_user', 'select_products', 'created_by');

        return view('frontend.checkOrders.edit', compact('checkOrder', 'select_products', 'select_users'));
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

        return redirect()->route('frontend.check-orders.index');
    }

    public function show(CheckOrder $checkOrder)
    {
        abort_if(Gate::denies('check_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkOrder->load('select_user', 'select_products', 'created_by');

        return view('frontend.checkOrders.show', compact('checkOrder'));
    }

    public function destroy(CheckOrder $checkOrder)
    {
        abort_if(Gate::denies('check_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyCheckOrderRequest $request)
    {
        $checkOrders = CheckOrder::find(request('ids'));

        foreach ($checkOrders as $checkOrder) {
            $checkOrder->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('check_order_create') && Gate::denies('check_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CheckOrder();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
