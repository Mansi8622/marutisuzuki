<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDisputeRequest;
use App\Http\Requests\StoreDisputeRequest;
use App\Http\Requests\UpdateDisputeRequest;
use App\Models\Dispute;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisputesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('dispute_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $disputes = Dispute::with(['customer', 'created_by','customers'])->get();
        

        return view('frontend.disputes.index', compact('disputes'));
    }

    public function create()
    {
        abort_if(Gate::denies('dispute_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.disputes.create');
    }

    public function store(StoreDisputeRequest $request)
    {
        $dispute = Dispute::create($request->all());

        return redirect()->route('frontend.disputes.index');
    }

    public function edit(Dispute $dispute)
    {
        abort_if(Gate::denies('dispute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dispute->load('customer', 'created_by');

        return view('frontend.disputes.edit', compact('dispute'));
    }

    public function update(UpdateDisputeRequest $request, Dispute $dispute)
    {
        $dispute->update($request->all());

        return redirect()->route('frontend.disputes.index');
    }

    public function show(Dispute $dispute)
    {
        abort_if(Gate::denies('dispute_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dispute->load('customer', 'created_by');

        return view('frontend.disputes.show', compact('dispute'));
    }

    public function destroy(Dispute $dispute)
    {
        abort_if(Gate::denies('dispute_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dispute->delete();

        return back();
    }

    public function massDestroy(MassDestroyDisputeRequest $request)
    {
        $disputes = Dispute::find(request('ids'));

        foreach ($disputes as $dispute) {
            $dispute->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
