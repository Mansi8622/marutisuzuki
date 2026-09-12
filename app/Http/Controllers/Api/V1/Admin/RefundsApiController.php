<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreRefundRequest;
use App\Http\Requests\UpdateRefundRequest;
use App\Http\Resources\Admin\RefundResource;
use App\Models\Refund;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefundsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('refund_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RefundResource(Refund::with(['product', 'company', 'order', 'created_by'])->get());
    }

    public function store(StoreRefundRequest $request)
    {
        $refund = Refund::create($request->all());

        foreach ($request->input('attachment', []) as $file) {
            $refund->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
        }

        return (new RefundResource($refund))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Refund $refund)
    {
        abort_if(Gate::denies('refund_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new RefundResource($refund->load(['product', 'company', 'order', 'created_by']));
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

        return (new RefundResource($refund))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Refund $refund)
    {
        abort_if(Gate::denies('refund_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refund->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
