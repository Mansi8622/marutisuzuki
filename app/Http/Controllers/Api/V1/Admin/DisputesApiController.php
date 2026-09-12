<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisputeRequest;
use App\Http\Requests\UpdateDisputeRequest;
use App\Http\Resources\Admin\DisputeResource;
use App\Models\Dispute;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisputesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dispute_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DisputeResource(Dispute::with(['customer', 'created_by'])->get());
    }

    public function store(StoreDisputeRequest $request)
    {
        $dispute = Dispute::create($request->all());

        return (new DisputeResource($dispute))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Dispute $dispute)
    {
        abort_if(Gate::denies('dispute_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new DisputeResource($dispute->load(['customer', 'created_by']));
    }

    public function update(UpdateDisputeRequest $request, Dispute $dispute)
    {
        $dispute->update($request->all());

        return (new DisputeResource($dispute))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Dispute $dispute)
    {
        abort_if(Gate::denies('dispute_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dispute->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
