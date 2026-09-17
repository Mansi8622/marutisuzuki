<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWalletRequestRequest;
use App\Http\Requests\StoreWalletRequestRequest;
use App\Http\Requests\UpdateWalletRequestRequest;
use App\Models\WalletRequest;
use App\Models\CheckOrder;
use App\Models\Transaction;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WalletRequestController extends Controller
{
    public function index()
    {
        $walletRequests = WalletRequest::with(['vendor', 'created_by'])
            ->where('vendor_id', auth()->id())->latest()->get();

        return view('frontend.walletRequests.index', compact('walletRequests'));
    }

    public function create()
    {
        return view('frontend.walletRequests.create');
    }

    public function statement()
    {
        $userId = auth()->id();
        $wallet = WalletRequest::where('vendor_id', $userId)->latest()->firstOrFail();
        $orders = CheckOrder::where('select_user_id', $userId)->where('payment_method', 'Credit Line')->latest()->get();
        $orders->each(function ($order) use ($userId) {
            $order->repaid = Transaction::where('vendor_id', $userId)->where('order_id', $order->id)
                ->whereIn('transaction_type', ['payout','cash','cheque','bank_transfer','upi','other'])->where('status', 'success')->sum('request_amount');
            $order->outstanding = max(0, $order->total_amount - $order->repaid);
        });
        return view('frontend.walletRequests.statement', compact('wallet', 'orders'));
    }

    public function store(StoreWalletRequestRequest $request)
    {
        if (WalletRequest::where('vendor_id', auth()->id())->where('status', 'Pending')->exists()) {
            return redirect()->route('frontend.wallet-requests.index')->with('message', 'Your credit request is already awaiting admin approval.');
        }
        WalletRequest::create(['vendor_id' => auth()->id(), 'created_by_id' => auth()->id(), 'welcome_amount' => $request->input('welcome_amount'), 'due' => 0, 'status' => 'Pending']);
        return redirect()->route('frontend.wallet-requests.index')->with('message', 'Credit line application submitted for admin approval.');
    }

    public function edit(WalletRequest $walletRequest)
    {
        abort_if(Gate::denies('wallet_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $walletRequest->load('vendor', 'created_by');

        return view('frontend.walletRequests.edit', compact('walletRequest'));
    }

    public function update(UpdateWalletRequestRequest $request, WalletRequest $walletRequest)
    {
        $walletRequest->update($request->all());

        return redirect()->route('frontend.wallet-requests.index');
    }

    public function show(WalletRequest $walletRequest)
    {
        abort_if(Gate::denies('wallet_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $walletRequest->load('vendor', 'created_by');

        return view('frontend.walletRequests.show', compact('walletRequest'));
    }

    public function destroy(WalletRequest $walletRequest)
    {
        abort_if(Gate::denies('wallet_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $walletRequest->delete();

        return back();
    }

    public function massDestroy(MassDestroyWalletRequestRequest $request)
    {
        $walletRequests = WalletRequest::find(request('ids'));

        foreach ($walletRequests as $walletRequest) {
            $walletRequest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
