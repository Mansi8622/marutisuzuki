<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCheckGodownRequest;
use App\Http\Requests\StoreCheckGodownRequest;
use App\Http\Requests\UpdateCheckGodownRequest;
use App\Models\CheckGodown;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGodownController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('check_godown_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkGodowns = CheckGodown::with(['select_product', 'created_by'])->get();

        return view('frontend.checkGodowns.index', compact('checkGodowns'));
    }

    public function create()
    {
        abort_if(Gate::denies('check_godown_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.checkGodowns.create', compact('select_products'));
    }

    public function store(StoreCheckGodownRequest $request)
    {
        $checkGodown = CheckGodown::create($request->all());

        return redirect()->route('frontend.check-godowns.index');
    }

    public function edit(CheckGodown $checkGodown)
    {
        abort_if(Gate::denies('check_godown_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $checkGodown->load('select_product', 'created_by');

        return view('frontend.checkGodowns.edit', compact('checkGodown', 'select_products'));
    }

    public function update(UpdateCheckGodownRequest $request, CheckGodown $checkGodown)
    {
        $checkGodown->update($request->all());

        return redirect()->route('frontend.check-godowns.index');
    }

    public function show(CheckGodown $checkGodown)
    {
        abort_if(Gate::denies('check_godown_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkGodown->load('select_product', 'created_by');

        return view('frontend.checkGodowns.show', compact('checkGodown'));
    }

    public function destroy(CheckGodown $checkGodown)
    {
        abort_if(Gate::denies('check_godown_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkGodown->delete();

        return back();
    }

    public function massDestroy(MassDestroyCheckGodownRequest $request)
    {
        $checkGodowns = CheckGodown::find(request('ids'));

        foreach ($checkGodowns as $checkGodown) {
            $checkGodown->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
