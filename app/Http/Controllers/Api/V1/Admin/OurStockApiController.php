<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOurStockRequest;
use App\Http\Requests\UpdateOurStockRequest;
use App\Http\Resources\Admin\OurStockResource;
use App\Models\OurStock;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OurStockApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('our_stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OurStockResource(OurStock::with(['select_product', 'created_by'])->get());
    }

    public function store(StoreOurStockRequest $request)
    {
        $ourStock = OurStock::create($request->all());

        return (new OurStockResource($ourStock))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(OurStock $ourStock)
    {
        abort_if(Gate::denies('our_stock_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new OurStockResource($ourStock->load(['select_product', 'created_by']));
    }

    public function update(UpdateOurStockRequest $request, OurStock $ourStock)
    {
        $ourStock->update($request->all());

        return (new OurStockResource($ourStock))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(OurStock $ourStock)
    {
        abort_if(Gate::denies('our_stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ourStock->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
