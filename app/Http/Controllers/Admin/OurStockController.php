<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOurStockRequest;
use App\Http\Requests\StoreOurStockRequest;
use App\Http\Requests\UpdateOurStockRequest;
use App\Models\OurStock;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OurStockController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('our_stock_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ourStocks = OurStock::with(['select_product', 'created_by'])->get();

        return view('admin.ourStocks.index', compact('ourStocks'));
    }

    public function create()
    {
        abort_if(Gate::denies('our_stock_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ourStocks.create', compact('select_products'));
    }

    public function store(StoreOurStockRequest $request)
    {
        $ourStock = OurStock::create($request->all());

        return redirect()->route('admin.our-stocks.index');
    }

    public function edit(OurStock $ourStock)
    {
        abort_if(Gate::denies('our_stock_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ourStock->load('select_product', 'created_by');

        return view('admin.ourStocks.edit', compact('ourStock', 'select_products'));
    }

    public function update(UpdateOurStockRequest $request, OurStock $ourStock)
    {
        $ourStock->update($request->all());

        return redirect()->route('admin.our-stocks.index');
    }

    public function show(OurStock $ourStock)
    {
        abort_if(Gate::denies('our_stock_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ourStock->load('select_product', 'created_by', 'selectProductStockTransfers');

        return view('admin.ourStocks.show', compact('ourStock'));
    }

    public function destroy(OurStock $ourStock)
    {
        abort_if(Gate::denies('our_stock_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ourStock->delete();

        return back();
    }

    public function massDestroy(MassDestroyOurStockRequest $request)
    {
        $ourStocks = OurStock::find(request('ids'));

        foreach ($ourStocks as $ourStock) {
            $ourStock->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
