<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyRefundRequest;
use App\Http\Requests\StoreRefundRequest;
use App\Http\Requests\UpdateRefundRequest;
use App\Models\Refund;
use App\Models\WalletRequest;
use App\Models\Transaction;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class RefundsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('refund_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refunds = Refund::with(['product', 'company', 'order', 'created_by', 'media'])->get();

        return view('frontend.refunds.index', compact('refunds'));
    }

    public function create()
    {
        abort_if(Gate::denies('refund_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.refunds.create');
    }

    public function store(StoreRefundRequest $request)
    {
        $refund = Refund::create($request->all());

        foreach ($request->input('attachment', []) as $file) {
            $refund->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $refund->id]);
        }

        return redirect()->route('frontend.refunds.index');
    }

    public function edit(Refund $refund)
    {
        abort_if(Gate::denies('refund_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refund->load('product', 'company', 'order', 'created_by');

        return view('frontend.refunds.edit', compact('refund'));
    }

    public function update(UpdateRefundRequest $request, Refund $refund)
    {
        // Check if refund is already approved
        if ($refund->status === 'Approved' && $request->status !== 'Approved') {
            // Prevent status change if already approved
            return back()->with('error', 'Status cannot be changed once approved.');
        }
        
        // Check if refund is being approved again
        if ($refund->status === 'Approved' && $request->status === 'Approved') {
            return back()->with('error', 'This refund is already approved.');
        }
    
        $refund->update($request->all());
        
        // Handle file attachments
        if (count($refund->attachment) > 0) {
            foreach ($refund->attachment as $media) {
                if (!in_array($media->file_name, $request->input('attachment', []))) {
                    $media->delete();
                }
            }
        }
        
        $media = $refund->attachment->pluck('file_name')->toArray();
        foreach ($request->input('attachment', []) as $file) {
            if (count($media) === 0 || !in_array($file, $media)) {
                $refund->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
            }
        }
    
        // Check if refund is approved
        if ($request->status === 'Approved') {
            $checkOrder = $refund->order; // Assuming refund is linked to an order
    
            if ($checkOrder && $checkOrder->payment_method === 'Credit Line') {
                $selectedUserId = $checkOrder->select_user_id;    
                
                // Update wallet_requests table
                $walletRequest = WalletRequest::where('vendor_id', $selectedUserId)->first();
    
                if ($walletRequest) {
                    $walletRequest->increment('welcome_amount', $checkOrder->total_amount);
                }
              
                // Create transaction record
                Transaction::create([
                    'vendor_id' => $selectedUserId, // Use select_user_id instead of logged-in user
                    'created_by_id' => $selectedUserId, // Use select_user_id instead of logged-in user
                    'request_amount' => $checkOrder->total_amount,
                    'transaction_type' => 'refunded',
                ]);
            }
        }
    
        return redirect()->route('frontend.refunds.index');
    }
    
    

    public function show(Refund $refund)
    {
        abort_if(Gate::denies('refund_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refund->load('product', 'company', 'order', 'created_by');

        return view('frontend.refunds.show', compact('refund'));
    }

    public function destroy(Refund $refund)
    {
        abort_if(Gate::denies('refund_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $refund->delete();

        return back();
    }

    public function massDestroy(MassDestroyRefundRequest $request)
    {
        $refunds = Refund::find(request('ids'));

        foreach ($refunds as $refund) {
            $refund->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('refund_create') && Gate::denies('refund_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Refund();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
