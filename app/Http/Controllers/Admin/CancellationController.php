<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCancellationRequest;
use App\Http\Requests\StoreCancellationRequest;
use App\Http\Requests\UpdateCancellationRequest;
use App\Models\Cancellation;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CancellationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('cancellation_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cancellations = Cancellation::with(['order_number', 'product', 'created_by'])->get();

        return view('admin.cancellations.index', compact('cancellations'));
    }

    public function create()
    {
        abort_if(Gate::denies('cancellation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.cancellations.create', compact('products'));
    }

    public function store(StoreCancellationRequest $request)
    {
        $cancellation = Cancellation::create($request->all());

        return redirect()->route('admin.cancellations.index');
    }

    public function edit(Cancellation $cancellation)
    {
        abort_if(Gate::denies('cancellation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cancellation->load('order_number', 'product', 'created_by');

        return view('admin.cancellations.edit', compact('cancellation', 'products'));
    }

    public function update(UpdateCancellationRequest $request, Cancellation $cancellation)
    {
        $cancellation->update($request->all());

        return redirect()->route('admin.cancellations.index');
    }

    public function show(Cancellation $cancellation)
    {
        abort_if(Gate::denies('cancellation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cancellation->load('order_number', 'product', 'created_by');

        return view('admin.cancellations.show', compact('cancellation'));
    }

    public function destroy(Cancellation $cancellation)
    {
        abort_if(Gate::denies('cancellation_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cancellation->delete();

        return back();
    }

    public function massDestroy(MassDestroyCancellationRequest $request)
    {
        $cancellations = Cancellation::find(request('ids'));

        foreach ($cancellations as $cancellation) {
            $cancellation->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
