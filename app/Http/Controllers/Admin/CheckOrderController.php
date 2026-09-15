<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCheckOrderRequest;
use App\Http\Requests\StoreCheckOrderRequest;
use App\Http\Requests\UpdateCheckOrderRequest;
use App\Models\CheckOrder;
use App\Models\Product;
use App\Models\User;
use App\Models\Carrier;
use App\Models\OurStock;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CheckOrderController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('check_order_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        $checkOrders = CheckOrder::with(['select_user', 'select_products', 'created_by', 'media'])->get();
    
        // Yahan OurStock se stock data nikal rahe hain
        $productStocks = OurStock::select('select_product_id', 'quantity_available')
            ->get()
            ->keyBy('select_product_id'); // product_id ke basis pe data set kar diya
    
        // View me checkOrders ke saath productStocks bhi bhej rahe hain
        return view('admin.checkOrders.index', compact('checkOrders', 'productStocks'));
    }
    

    public function create()
    {
        abort_if(Gate::denies('check_order_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.checkOrders.create');
    }

    public function store(StoreCheckOrderRequest $request)
    {
        $checkOrder = CheckOrder::create($request->all());

        foreach ($request->input('attachment', []) as $file) {
            $checkOrder->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('attachment');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $checkOrder->id]);
        }

        return redirect()->route('admin.check-orders.index');
    }

    public function edit(CheckOrder $checkOrder)
    {
        abort_if(Gate::denies('check_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        $select_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
    
        $products = Product::with(['our_stock' => function ($query) {
            $query->select('id', 'select_product_id', 'quantity_available');
        }])->select('id', 'name', 'quantity')->get()->keyBy('id');
    
        $carriers = Carrier::pluck('carrier_name', 'id')->prepend(trans('global.pleaseSelect'), '');
    
        $checkOrder->load(['select_user', 'select_products' => function ($query) {
            $query->withPivot('quantity');
        }, 'carrier', 'created_by']);
    
        return view('admin.checkOrders.edit', compact('checkOrder', 'select_users', 'products', 'carriers'));
    }
    
    
    

    public function update(UpdateCheckOrderRequest $request, CheckOrder $checkOrder)
{
    $request->validate([
        'confirm_qty' => ['nullable', 'array'],
        'refund_credit' => ['nullable', 'boolean'],
        'fulfilment_note' => ['nullable', 'string', 'max:2000'],
    ]);

    DB::transaction(function () use ($request, $checkOrder) {
        $ordered = $this->decodeOrderProducts($checkOrder->products);
        $previous = json_decode($checkOrder->confirm_qty ?: '[]', true) ?: [];
        $requested = $request->input('confirm_qty', []);
        $confirmed = [];
        $confirmedAmount = 0;

        foreach ($ordered as $productId => $line) {
            $orderedQty = (int) ($line['quantity'] ?? 0);
            $newQty = in_array((string) $productId, array_map('strval', $request->input('select_products', [])), true)
                ? min($orderedQty, max(0, (int) ($requested[$productId] ?? 0))) : 0;
            $oldQty = (int) ($previous[$productId] ?? 0);
            $delta = $newQty - $oldQty;
            $product = Product::findOrFail($productId);
            $stock = OurStock::where('select_product_id', $productId)->lockForUpdate()->first();

            if ($delta > 0 && (!$stock || $delta > (int) $stock->quantity_available)) {
                throw \Illuminate\Validation\ValidationException::withMessages(['confirm_qty' => "Insufficient stock for {$product->name}."]);
            }
            if ($stock && $delta !== 0) $stock->update(['quantity_available' => max(0, (int) $stock->quantity_available - $delta)]);
            $confirmed[$productId] = $newQty;
            $unitPrice = (float) ($line['unit_price'] ?? $product->price_1 ?? $product->price ?? 0);
            $gst = (float) ($line['gst'] ?? $product->gst ?? 0);
            $confirmedAmount += $newQty * $unitPrice * (1 + $gst / 100);
        }

        $confirmedAmount = round($confirmedAmount, 2);

        // A partial fulfilment must have a customer-facing explanation.  This is
        // kept server-side so the requirement cannot be bypassed from the form.
        if ($confirmedAmount < (float) $checkOrder->total_amount && blank($request->input('fulfilment_note'))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'fulfilment_note' => 'Please add a reason for the unconfirmed item or quantity.',
            ]);
        }

        $checkOrder->fill($request->only(['select_user_id', 'placed_at', 'order_status', 'carrier_id', 'notes']));
        $checkOrder->confirm_qty = json_encode($confirmed);
        $checkOrder->confirmed_amount = $confirmedAmount;
        $checkOrder->fulfilment_note = $request->input('fulfilment_note');
        $checkOrder->save();

        // Razorpay/online payments never alter their payment total. Credit returns are ledger entries.
        $refundable = max(0, (float) $checkOrder->total_amount - $confirmedAmount);
        if ((float) $checkOrder->credit_refund_amount > 0
            && $confirmedAmount > ((float) $checkOrder->total_amount - (float) $checkOrder->credit_refund_amount)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'confirm_qty' => 'This order already has a credit-line return. Confirmed quantity cannot be increased.',
            ]);
        }
        if ($request->boolean('refund_credit') && strcasecmp((string) $checkOrder->payment_method, 'Credit Line') === 0 && $refundable > (float) $checkOrder->credit_refund_amount) {
            $refund = $refundable - (float) $checkOrder->credit_refund_amount;
            $wallet = \App\Models\WalletRequest::where('vendor_id', $checkOrder->select_user_id)->lockForUpdate()->firstOrFail();
            $wallet->increment('welcome_amount', $refund);
            $wallet->update(['due' => max(0, (float) $wallet->due - $refund)]);
            \App\Models\Transaction::create([
                'vendor_id' => $checkOrder->select_user_id, 'order_id' => $checkOrder->id, 'order_number' => $checkOrder->order_number,
                'transaction_id' => 'CREDIT-RETURN-' . strtoupper(uniqid()), 'transaction_type' => 'refund',
                'request_amount' => $refund, 'paid_amount' => 0, 'total_amount' => $refund,
                'created_by_id' => auth()->id(), 'status' => 'success',
            ]);
            $checkOrder->increment('credit_refund_amount', $refund);
        }
    });

    // Handle media attachments
    $existingMediaFiles = $checkOrder->getMedia('attachment')->pluck('file_name')->toArray();
    $submittedAttachments = $request->input('attachment', []);

    // Delete removed files
    foreach ($checkOrder->getMedia('attachment') as $media) {
        if (!in_array($media->file_name, $submittedAttachments)) {
            $media->delete();
        }
    }

    // Add new uploaded files
    foreach ($submittedAttachments as $file) {
        if (!in_array($file, $existingMediaFiles)) {
            $checkOrder->addMedia(storage_path('tmp/uploads/' . basename($file)))
                ->toMediaCollection('attachment');
        }
    }

    return redirect()->route('admin.check-orders.index')->with('success', 'Check Order updated successfully.');
}

    private function decodeOrderProducts($products): array
    {
        $decoded = is_string($products) ? json_decode($products, true) : $products;
        if (is_array($decoded) && isset($decoded[0]) && is_string($decoded[0])) $decoded = json_decode($decoded[0], true);
        if (! is_array($decoded)) return [];

        // Historic orders use a product-id keyed cart; newer clients may send a
        // regular JSON list. Normalize both forms for fulfilment processing.
        $lines = [];
        foreach ($decoded as $key => $line) {
            if (! is_array($line)) continue;
            $productId = $line['id'] ?? (is_numeric($key) ? $key : null);
            if ($productId) $lines[$productId] = $line;
        }
        return $lines;
    }

    
    
    
    public function show(CheckOrder $checkOrder)
    {
        abort_if(Gate::denies('check_order_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkOrder->load('select_user', 'select_products', 'created_by', 'orderNumberCancellations', 'orderRefunds');

        return view('admin.checkOrders.show', compact('checkOrder'));
    }

    public function destroy(CheckOrder $checkOrder)
    {
        abort_if(Gate::denies('check_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $checkOrder->delete();

        return back();
    }

    public function massDestroy(MassDestroyCheckOrderRequest $request)
    {
        $checkOrders = CheckOrder::find(request('ids'));

        foreach ($checkOrders as $checkOrder) {
            $checkOrder->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('check_order_create') && Gate::denies('check_order_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CheckOrder();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
    public function checkStockAvailability($productId, $quantityNeeded)
    {
    // Get the stock for the specific product
    $stock = OurStock::where('select_product_id', $productId)->first();

    if ($stock) {
        // Check if the quantity in stock is greater than or equal to the required quantity
        if ($stock->quantity_available >= $quantityNeeded) {
            return [
                'status' => 'success',
                'message' => 'Stock Available: ' . $stock->quantity_available . ' items in stock.',
            ];
        } else {
            return [
                'status' => 'warning',
                'message' => 'Warning: Stock is insufficient. Please maintain stock.',
            ];
        }
    }

    return [
        'status' => 'error',
        'message' => 'Error: Product stock not found.',
    ];
    }
}
