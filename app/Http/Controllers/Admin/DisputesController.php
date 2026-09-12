<?php

namespace App\Http\Controllers\Admin;

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

        $disputes = Dispute::with(['customer', 'created_by'])->get();

        return view('admin.disputes.index', compact('disputes'));
    }

    public function create()
    {
        abort_if(Gate::denies('dispute_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.disputes.create');
    }

    public function store(StoreDisputeRequest $request)
    {
        $dispute = Dispute::create($request->all());

        return redirect()->route('admin.disputes.index');
    }

    public function edit(Dispute $dispute)
    {
        abort_if(Gate::denies('dispute_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dispute->load('customer', 'created_by');

        return view('admin.disputes.edit', compact('dispute'));
    }

    public function update(UpdateDisputeRequest $request, Dispute $dispute)
    {
        $dispute->update($request->all());

        return redirect()->route('admin.disputes.index');
    }

    public function show(Dispute $dispute)
    {
        abort_if(Gate::denies('dispute_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dispute->load('customer', 'created_by');

        return view('admin.disputes.show', compact('dispute'));
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
