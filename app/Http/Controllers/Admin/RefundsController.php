<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRefundRequest;
use App\Http\Requests\StoreRefundRequest;
use App\Http\Requests\UpdateRefundRequest;
use App\Models\Refund;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class RefundsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('refund_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refunds = Refund::with(['product', 'company', 'order', 'created_by', 'media'])->get();

        return view('admin.refunds.index', compact('refunds'));
    }

    public function create()
    {
        abort_if(Gate::denies('refund_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.refunds.create');
    }

    public function store(StoreRefundRequest $request)
    {
        $refund = Refund::create($request->all());

        foreach ($request->input('attachment', []) as $file) {
            $refund->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $refund->id]);
        }

        return redirect()->route('admin.refunds.index');
    }

    public function edit(Refund $refund)
    {
        abort_if(Gate::denies('refund_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refund->load('product', 'company', 'order', 'created_by');

        return view('admin.refunds.edit', compact('refund'));
    }

    public function update(UpdateRefundRequest $request, Refund $refund)
    {
        $refund->update($request->all());

        if (count($refund->attachment) > 0) {
            foreach ($refund->attachment as $media) {
                if (! in_array($media->file_name, $request->input('attachment', []))) {
                    $media->delete();
                }
            }
        }
        $media = $refund->attachment->pluck('file_name')->toArray();
        foreach ($request->input('attachment', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $refund->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
            }
        }

        return redirect()->route('admin.refunds.index');
    }

    public function show(Refund $refund)
    {
        abort_if(Gate::denies('refund_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refund->load('product', 'company', 'order', 'created_by');

        return view('admin.refunds.show', compact('refund'));
    }

    public function destroy(Refund $refund)
    {
        abort_if(Gate::denies('refund_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refund->delete();

        return back();
    }

    public function massDestroy(MassDestroyRefundRequest $request)
    {
        $refunds = Refund::find(request('ids'));

        foreach ($refunds as $refund) {
            $refund->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('refund_create') && Gate::denies('refund_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Refund();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
