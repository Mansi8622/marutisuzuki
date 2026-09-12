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
    // Update basic check order fields
    $checkOrder->update($request->all());

    // Decode existing confirmed quantities
    $existingConfirmQtyRaw = $checkOrder->confirm_qty;
    $existingConfirmQty = is_string($existingConfirmQtyRaw)
        ? json_decode($existingConfirmQtyRaw, true)
        : (is_array($existingConfirmQtyRaw) ? $existingConfirmQtyRaw : []);
    
    // New confirm quantities from form
    $newConfirmQuantities = $request->input('confirm_qty', []);
    $productIds = $request->input('select_products', []);

    $productData = [];
    $updatedConfirmQty = [];

    foreach ($productIds as $productId) {
        $product = Product::find($productId);
        $newConfirmedQty = isset($newConfirmQuantities[$productId]) ? (int) $newConfirmQuantities[$productId] : 0;

        $existingQty = isset($existingConfirmQty[$productId]) ? (int) $existingConfirmQty[$productId] : 0;
        $totalConfirmedQty = $existingQty + $newConfirmedQty;

        // Fetch stock entry
        $stock = OurStock::where('select_product_id', $productId)->first();

        // Check if sufficient stock is available for the new quantity
        if (!$stock || $newConfirmedQty > $stock->quantity_available) {
            return back()->withErrors([
                'confirm_quantity' => "Confirmed quantity for '{$product->name}' exceeds available stock."
            ])->withInput();
        }

        // Update stock
        $stock->quantity_available -= $newConfirmedQty;
        $stock->save();

        // Prepare updated confirm_qty and pivot data
        $updatedConfirmQty[$productId] = $totalConfirmedQty;
        $productData[$productId] = ['quantity' => $newConfirmedQty];
    }

    // Save updated confirm_qty as JSON
    $checkOrder->confirm_qty = json_encode($updatedConfirmQty);
    $checkOrder->save();

    // Sync pivot with new confirmed quantities only
    $checkOrder->select_products()->sync($productData);

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
