<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWalletRequestRequest;
use App\Http\Requests\StoreWalletRequestRequest;
use App\Http\Requests\UpdateWalletRequestRequest;
use App\Models\WalletRequest;
use App\Models\Transaction;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WalletRequestController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('wallet_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $walletRequests = WalletRequest::with(['vendor', 'created_by'])->get();

        return view('admin.walletRequests.index', compact('walletRequests'));
    }

    public function create()
    {
        abort_if(Gate::denies('wallet_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.walletRequests.create');
    }

    public function store(StoreWalletRequestRequest $request)
    {
        $walletRequest = WalletRequest::create($request->all());
    
        // Total purchase amount nikalna
        $totalPurchaseAmount = Transaction::where('vendor_id', $walletRequest->vendor_id)
            ->where('transaction_type', 'purchase')
            ->sum('request_amount');
    
        // due field me purchase amount ka total set karein
        $walletRequest->update([
            'due' => $totalPurchaseAmount
        ]);
    
        return redirect()->route('admin.wallet-requests.index')->with('message', 'Wallet request created successfully');
    }
    
    

    public function edit(WalletRequest $walletRequest)
    {
        abort_if(Gate::denies('wallet_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $walletRequest->load('vendor', 'created_by');

        return view('admin.walletRequests.edit', compact('walletRequest'));
    }

    public function update(UpdateWalletRequestRequest $request, WalletRequest $walletRequest)
    {
        // Total purchase amount nikalna
        $totalPurchaseAmount = Transaction::where('vendor_id', $walletRequest->vendor_id)
            ->where('transaction_type', 'purchase')
            ->sum('request_amount');
    
        // Wallet request ko update karna
        $walletRequest->update(array_merge(
            $request->all(),
            ['due' => $totalPurchaseAmount] // due ko purchase amount ka total set karna
        ));
    
        return redirect()->route('admin.wallet-requests.index')->with('message', 'Wallet request updated successfully');
    }
    
    

    public function show(WalletRequest $walletRequest)
    {
        abort_if(Gate::denies('wallet_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $walletRequest->load('vendor', 'created_by');

        return view('admin.walletRequests.show', compact('walletRequest'));
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
    public function applyWallet(Request $request)
    {
        try {
            $user = Auth::user();
    
            // Check if the user has already applied for a wallet and the request is still pending
            $existingRequest = WalletRequest::where('vendor_id', $user->id)
                                             ->where('status', 'pending')
                                             ->first();
    
            if ($existingRequest) {
                return response()->json(['error' => 'You have already applied for a wallet. Please wait for approval.']);
            }
    
            // Insert new wallet request
            WalletRequest::create([
                'vendor_id' => $user->id,
                'created_by_id' => $user->id,
                'status' => 'pending',
                'welcome_amount' => 0, // You can change this if there's an initial amount
                'due' => 0, // You can set this if there's a due amount at this stage
            ]);
    
            return response()->json(['success' => 'Wallet request submitted successfully. Please wait for approval.']);
    
        } catch (\Exception $e) {
            // Catch any errors and return them in the response
            return response()->json(['error' => 'Something went wrong. Please try again.', 'message' => $e->getMessage()]);
        }
    }

   


        // Function to check wallet balance, status, and proceed with deduction and transaction


    

}
