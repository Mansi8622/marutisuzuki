<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStockTransferRequest;
use App\Http\Requests\StoreStockTransferRequest;
use App\Http\Requests\UpdateStockTransferRequest;
use App\Models\OurStock;
use App\Models\StockTransfer;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StockTransferController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('stock_transfer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockTransfers = StockTransfer::with(['select_products', 'select_user', 'created_by'])->get();

        return view('frontend.stockTransfers.index', compact('stockTransfers'));
    }

    public function create()
    {
        abort_if(Gate::denies('stock_transfer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_products = OurStock::pluck('sku', 'id');

        $select_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.stockTransfers.create', compact('select_products', 'select_users'));
    }

    public function store(StoreStockTransferRequest $request)
    {
        $stockTransfer = StockTransfer::create($request->all());
        $stockTransfer->select_products()->sync($request->input('select_products', []));

        return redirect()->route('frontend.stock-transfers.index');
    }

    public function edit(StockTransfer $stockTransfer)
    {
        abort_if(Gate::denies('stock_transfer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_products = OurStock::pluck('sku', 'id');

        $select_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $stockTransfer->load('select_products', 'select_user', 'created_by');

        return view('frontend.stockTransfers.edit', compact('select_products', 'select_users', 'stockTransfer'));
    }

    public function update(UpdateStockTransferRequest $request, StockTransfer $stockTransfer)
    {
        $stockTransfer->update($request->all());
        $stockTransfer->select_products()->sync($request->input('select_products', []));

        return redirect()->route('frontend.stock-transfers.index');
    }

    public function show(StockTransfer $stockTransfer)
    {
        abort_if(Gate::denies('stock_transfer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockTransfer->load('select_products', 'select_user', 'created_by');

        return view('frontend.stockTransfers.show', compact('stockTransfer'));
    }

    public function destroy(StockTransfer $stockTransfer)
    {
        abort_if(Gate::denies('stock_transfer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockTransfer->delete();

        return back();
    }

    public function massDestroy(MassDestroyStockTransferRequest $request)
    {
        $stockTransfers = StockTransfer::find(request('ids'));

        foreach ($stockTransfers as $stockTransfer) {
            $stockTransfer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
