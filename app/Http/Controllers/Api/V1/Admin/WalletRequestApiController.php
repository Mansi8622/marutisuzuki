<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletRequestRequest;
use App\Http\Requests\UpdateWalletRequestRequest;
use App\Http\Resources\Admin\WalletRequestResource;
use App\Models\WalletRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WalletRequestApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wallet_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WalletRequestResource(WalletRequest::with(['vendor', 'created_by'])->get());
    }

    public function store(StoreWalletRequestRequest $request)
    {
        $walletRequest = WalletRequest::create($request->all());

        return (new WalletRequestResource($walletRequest))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(WalletRequest $walletRequest)
    {
        abort_if(Gate::denies('wallet_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new WalletRequestResource($walletRequest->load(['vendor', 'created_by']));
    }

    public function update(UpdateWalletRequestRequest $request, WalletRequest $walletRequest)
    {
        $walletRequest->update($request->all());

        return (new WalletRequestResource($walletRequest))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(WalletRequest $walletRequest)
    {
        abort_if(Gate::denies('wallet_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $walletRequest->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
