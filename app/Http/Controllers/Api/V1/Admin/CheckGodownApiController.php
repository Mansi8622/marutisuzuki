<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCheckGodownRequest;
use App\Http\Requests\UpdateCheckGodownRequest;
use App\Http\Resources\Admin\CheckGodownResource;
use App\Models\CheckGodown;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGodownApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('check_godown_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckGodownResource(CheckGodown::with(['select_product', 'created_by'])->get());
    }

    public function store(StoreCheckGodownRequest $request)
    {
        $checkGodown = CheckGodown::create($request->all());

        return (new CheckGodownResource($checkGodown))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(CheckGodown $checkGodown)
    {
        abort_if(Gate::denies('check_godown_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new CheckGodownResource($checkGodown->load(['select_product', 'created_by']));
    }

    public function update(UpdateCheckGodownRequest $request, CheckGodown $checkGodown)
    {
        $checkGodown->update($request->all());

        return (new CheckGodownResource($checkGodown))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(CheckGodown $checkGodown)
    {
        abort_if(Gate::denies('check_godown_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkGodown->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
