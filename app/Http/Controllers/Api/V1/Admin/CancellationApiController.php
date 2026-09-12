<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCancellationRequest;
use App\Http\Requests\UpdateCancellationRequest;
use App\Http\Resources\Admin\CancellationResource;
use App\Models\Cancellation;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CancellationApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cancellation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CancellationResource(Cancellation::with(['order_number', 'product', 'created_by'])->get());
    }

    public function store(StoreCancellationRequest $request)
    {
        $cancellation = Cancellation::create($request->all());

        return (new CancellationResource($cancellation))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Cancellation $cancellation)
    {
        abort_if(Gate::denies('cancellation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CancellationResource($cancellation->load(['order_number', 'product', 'created_by']));
    }

    public function update(UpdateCancellationRequest $request, Cancellation $cancellation)
    {
        $cancellation->update($request->all());

        return (new CancellationResource($cancellation))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Cancellation $cancellation)
    {
        abort_if(Gate::denies('cancellation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cancellation->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
