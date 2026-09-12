<?php

namespace App\Http\Controllers\Frontend;

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
        $user = auth()->user();
        $companyName = $user->business_name;
    
        // Check if the user is an admin
        if ($user->roles->contains('title', 'Admin')) {
            // Admin sees all cancellations
            $cancellations = Cancellation::with(['order_number', 'product', 'created_by', 'addCompany'])
                ->get();
        } else {
            // Company users see cancellations only related to their company
            $cancellations = Cancellation::with(['order_number', 'product', 'created_by', 'addCompany'])
                ->whereHas('addCompany', function ($query) use ($companyName) {
                    $query->where('company_name', $companyName);
                })
                ->get();
        }
    
        
    
        return view('frontend.cancellations.index', compact('cancellations'));
    }
    
    
    
    
    

    public function create()
    {
        abort_if(Gate::denies('cancellation_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.cancellations.create', compact('products'));
    }

    public function store(StoreCancellationRequest $request)
    {
        $cancellation = Cancellation::create($request->all());

        return redirect()->route('frontend.cancellations.index');
    }

    public function edit(Cancellation $cancellation)
    {
        abort_if(Gate::denies('cancellation_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cancellation->load('order_number', 'product', 'created_by');

        return view('frontend.cancellations.edit', compact('cancellation', 'products'));
    }

    public function update(UpdateCancellationRequest $request, Cancellation $cancellation)
    {
        $cancellation->update($request->all());
    
        // Determine user type based on guard
        $user = auth()->user();
        $userId = auth()->guard('web')->check() ? $user->id : null;
        $customerId = auth()->guard('customer')->check() ? $user->id : null;
    
        // Check if a dispute entry exists for this cancellation
        $dispute = \DB::table('disputes')->where('cancellation_id', $cancellation->id)->first();
    
        if ($dispute) {
            // If dispute exists, update it
            \DB::table('disputes')->where('cancellation_id', $cancellation->id)->update([
                'refund_request' => $cancellation->requested_at, // Requested_at from cancellation
                'response'       => $cancellation->status, // Status from cancellation
                'last_update'    => now(),
                'customer_id'    => $customerId,
                'user_id'        => $userId,
                'updated_at'     => now(),
            ]);
        } else {
            // If dispute does not exist, create a new one
            \DB::table('disputes')->insert([
                'order_id'       => $cancellation->order_id ?? null,
                'cancellation_id'=> $cancellation->id,
                'type'           => 'cancellation',
                'refund_request' => $cancellation->requested_at, // Requested_at from cancellation
                'response'       => $cancellation->status, // Status from cancellation
                'last_update'    => now(),
                'customer_id'    => $customerId,
                'user_id'        => $userId,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    
        // **If status is "approved", insert data into refunds table**
        if ($cancellation->status === 'Approved') {
            \DB::table('refunds')->insert([
                'status' =>'Processing',
                'product_id'  => $cancellation->product_id ?? null,
                'company_id'  => $cancellation->company_id ?? null,
                'order_id'    => $cancellation->order_id ?? null,
                'customer_id' => $customerId,
                'user_id'     => $userId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    
        return redirect()->route('frontend.cancellations.index');
    }
    
    
    
    

    public function show(Cancellation $cancellation)
    {
        abort_if(Gate::denies('cancellation_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $cancellation->load('order_number', 'product', 'created_by');

        return view('frontend.cancellations.show', compact('cancellation'));
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
