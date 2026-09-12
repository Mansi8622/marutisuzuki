<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStockTransferRequest;
use App\Http\Requests\StoreStockTransferRequest;
use App\Http\Requests\UpdateStockTransferRequest;
use App\Models\OurStock;
use App\Models\StockTransfer;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StockTransferController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('stock_transfer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockTransfers = StockTransfer::with(['select_products', 'select_user', 'created_by'])->get();

        return view('admin.stockTransfers.index', compact('stockTransfers'));
    }

    public function create()
    {
        abort_if(Gate::denies('stock_transfer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        // Fetch products with id, sku, and quantity_available
        $select_products = OurStock::select('id', 'sku', 'quantity_available')->get();
    
        // Fetch users for selection
        $select_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
    
        return view('admin.stockTransfers.create', compact('select_products', 'select_users'));
    }

    public function store(StoreStockTransferRequest $request)
    {
        // Create the stock transfer
        $stockTransfer = StockTransfer::create($request->all());
    
        // Get the selected products and their quantities
        $selectedProducts = $request->input('select_products', []); // Array of product IDs
        
        $quantities = $request->input('quantities', []); // Associative array: product_id => quantity
   
        // Loop through the selected products and update the stock
        foreach ($selectedProducts as $productId) {
            if (isset($quantities[$productId])) {
                $quantityTransferred = $quantities[$productId]; // Get the quantity for this product
    
                // Find the product in the stock
                $product = OurStock::find($productId);
                if ($product) {
                    // Check if stock is sufficient
                    if ($product->quantity_available >= $quantityTransferred) {
                        // Decrease the stock
                        $product->quantity_available -= $quantityTransferred;
                        $product->save();
    
                        // Attach the product to the stock transfer with the quantity
                        $stockTransfer->select_products()->attach($productId, ['quantity' => $quantityTransferred]);
                    } else {
                        // Handle insufficient stock
                        return redirect()->back()->with(
                            'error',
                            'Insufficient stock available for product SKU: ' . $product->sku
                        );
                    }
                }
            }
        }
    
        return redirect()->route('admin.stock-transfers.index')->with('success', 'Stock transfer created successfully.');
    }
    

    public function edit(StockTransfer $stockTransfer)
    {
        abort_if(Gate::denies('stock_transfer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $select_products = OurStock::pluck('sku', 'id');
        $select_users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $stockTransfer->load('select_products', 'select_user', 'created_by');

        return view('admin.stockTransfers.edit', compact('select_products', 'select_users', 'stockTransfer'));
    }

    public function update(UpdateStockTransferRequest $request, StockTransfer $stockTransfer)
    {
        // Get the previous products and their quantities for comparison
        $previousProducts = $stockTransfer->select_products()->pluck('pivot.quantity', 'id')->toArray();
    
        // Update the stock transfer data
        $stockTransfer->update($request->all());
        $selectedProducts = $request->input('select_products', []);
        $quantities = $request->input('quantity', []);
        $stockTransfer->select_products()->sync([]);

        // Loop through the updated products to adjust quantities in OurStock
        foreach ($selectedProducts as $index => $productId) {
            $quantityTransferred = $quantities[$index]; // Get the quantity for each product
            
            // Get the product
            $product = OurStock::find($productId);

            if ($product) {
                // If the product was previously selected and the quantity has changed
                $previousQuantity = isset($previousProducts[$productId]) ? $previousProducts[$productId] : 0;
                $quantityChange = $quantityTransferred - $previousQuantity;

                // Check if available quantity is sufficient before changing
                if ($product->quantity_available >= $quantityChange) {
                    // Adjust quantity in OurStock based on the change
                    $product->quantity_available -= $quantityChange;
                    $product->save();

                    // Update the quantity in the pivot table
                    $stockTransfer->select_products()->attach($productId, ['quantity' => $quantityTransferred]);
                } else {
                    // Handle error when stock is insufficient
                    return redirect()->back()->with('error', 'Insufficient stock available for product SKU: ' . $product->sku);
                }
            }
        }

        return redirect()->route('admin.stock-transfers.index');
    }

    public function show(StockTransfer $stockTransfer)
    {
        abort_if(Gate::denies('stock_transfer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stockTransfer->load('select_products', 'select_user', 'created_by');

        return view('admin.stockTransfers.show', compact('stockTransfer'));
    }

    public function destroy(StockTransfer $stockTransfer)
    {
        abort_if(Gate::denies('stock_transfer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Restore stock quantity in OurStock before deleting the stock transfer
        foreach ($stockTransfer->select_products as $product) {
            $product->quantity_available += $product->pivot->quantity; // Add back the quantity
            $product->save();
        }

        $stockTransfer->delete();

        return back();
    }

    public function massDestroy(MassDestroyStockTransferRequest $request)
    {
        $stockTransfers = StockTransfer::find(request('ids'));

        foreach ($stockTransfers as $stockTransfer) {
            // Restore stock quantity in OurStock before deleting each stock transfer
            foreach ($stockTransfer->select_products as $product) {
                $product->quantity_available += $product->pivot->quantity; // Add back the quantity
                $product->save();
            }

            $stockTransfer->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function downloadPdf($id)
    {
        $stockTransfer = StockTransfer::with(['select_products', 'select_user'])->findOrFail($id);

        return view('admin.stockTransfers.pdf', compact('stockTransfer'));
    }
}
