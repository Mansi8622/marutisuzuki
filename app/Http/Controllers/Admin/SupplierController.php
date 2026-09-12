<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\AddCompany;
use App\Models\OurStock;
use Illuminate\Support\Str; // Make sure to import
use Illuminate\Support\Facades\Log;


use App\Models\ProductSupplier;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::paginate(10); // Change from get() to paginate()
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|string|max:20',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pin_code' => 'required|string|max:10',
            'full_address' => 'required|string',
            'gst_number' => 'nullable|string|unique:suppliers,gst_number',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            'gst_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // GST document upload
        $gst_document = null;
        if ($request->hasFile('gst_document')) {
            $gst_document = $request->file('gst_document')->store('gst_documents', 'public');
        }

        Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'state' => $request->state,
            'city' => $request->city,
            'pin_code' => $request->pin_code,
            'full_address' => $request->full_address,
            'gst_number' => $request->gst_number,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'ifsc_code' => $request->ifsc_code,
            'gst_document' => $gst_document,
        ]);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier added successfully!');
    }

    public function show(Supplier $supplier)
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'required|string|max:20',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'pin_code' => 'required|string|max:10',
            'full_address' => 'required|string',
            'gst_number' => 'required|string|unique:suppliers,gst_number,' . $supplier->id,
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
            'gst_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('gst_document')) {
            $gst_document = $request->file('gst_document')->store('gst_documents', 'public');
            $supplier->gst_document = $gst_document;
        }

        $supplier->update($request->except(['gst_document']));

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return back()->with('success', 'Supplier deleted successfully!');
    }

    public function Products()
    {
        $suppliers = Supplier::all();
        $companies = \App\Models\AddCompany::with('products')->get(); // eager-load products
        return view('admin.suppliers.supplier_add_product', compact('suppliers', 'companies'));
    }
    

    public function storeProduct(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'company_id' => 'required|exists:add_companies,id',
            'purchasing_date' => 'required|date',
            'bill_number' => 'required|string',
            'bill_amount' => 'required|numeric|min:0',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.total' => 'required|numeric|min:0',
        ]);
 
        try {
            $companyId = $request->company_id;
            $products = $request->products;
            $validatedProducts = [];
    
            foreach ($products as $product) {
                // Validate if the product belongs to the selected company
                $productModel = AddCompany::find($companyId)
                    ->products()
                    ->where('id', $product['product_id'])
                    ->first();
    
                if (!$productModel) {
                    return back()->withErrors("Product ID {$product['product_id']} does not belong to the selected company.")->withInput();
                }
    
                $validatedProducts[] = [
                    'product_id'        => $product['product_id'],
                    'name'              => $product['name'] ?? $productModel->name,
                    'sku'               => $product['sku'] ?? $productModel->sku,
                    'hsn_code'          => $product['hsn_code'] ?? $productModel->hsn_code,
                    'price'             => $product['price'],
                    'quantity'          => $product['quantity'],
                    'total'             => ($product['price'] == 0) ? 'Free' : $product['price'] * $product['quantity'],
                ];
    
                // Update stock in the products table
                $productModel->increment('quantity', $product['quantity']);
    
                // Update stock in the our_stocks table
                OurStock::where('select_product_id', $product['product_id'])
                    ->increment('quantity_available', $product['quantity']);
            }
    
            // Use dd to inspect the data before inserting
            // dd([
            //     'supplier_id'     => $request->supplier_id,
            //     'company_id'      => $companyId,
            //     'bill_number'     => $request->bill_number,
            //     'purchasing_date' => $request->purchasing_date,
            //     'bill_amount'     => $request->bill_amount,
            //     'products'        => json_encode($validatedProducts),  // Store product details as JSON
            // ]);
            $billingId = rand(1000, 9999) . strtoupper(Str::random(4));
            foreach ($validatedProducts as $product) {
                ProductSupplier::create([
                    'supplier_id'     => $request->supplier_id,
                    'company_id'      => $companyId,
                    'bill_number'     => $request->bill_number,
                    'purchasing_date' => $request->purchasing_date,
                    'bill_amount'     => $request->bill_amount,
                    'product_id'      => $product['product_id'],
                    'quantity'      => $product['quantity'],
                    'purchasing_amount'=>$product['price'],
                    'products' => json_encode($product),
                    'billing_id'=>$billingId,
                    
                ]);
            }
            
            return redirect()->route('admin.suppliers.listProduct')
                ->with('success', "Products saved successfully and stock updated.");
        } catch (\Exception $e) {
            \Log::error('StoreProduct Error: ' . $e->getMessage());
            return back()->withErrors('An error occurred while saving products: ' . $e->getMessage())->withInput();
        }
    }
    
    
    public function List()
    {
        $supplierProducts = ProductSupplier::with('product', 'supplier')
            ->orderBy('purchasing_date', 'desc')
            ->get()
            ->groupBy('billing_id');
    
        return view('admin.suppliers.supplier_product_list', compact('supplierProducts'));
    }
    
    public function showProduct($id)
    {
    $supplierProduct = ProductSupplier::with('product', 'supplier')->findOrFail($id);
    return view('admin.suppliers.supplier_view_product', compact('supplierProduct'));
    }

    public function Productedit($id)
    {
        $supplierProduct = ProductSupplier::findOrFail($id);
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('admin.suppliers.supplier_edit_product', compact('supplierProduct', 'suppliers', 'products'));
    }
    public function Productupdate(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|numeric|min:1',
            'price'       => 'required|numeric|min:1',
            'discount'    => 'nullable|numeric|min:0',
            'price_1'     => 'nullable|numeric|min:0',
            'price_2'     => 'nullable|numeric|min:0',
            'price_3'     => 'nullable|numeric|min:0',
        ]);

        $supplierProduct = ProductSupplier::findOrFail($id);

        // Update stock
        $previousQuantity = $supplierProduct->quantity;
        $difference = $request->quantity - $previousQuantity;
        OurStock::where('select_product_id', $request->product_id)->increment('quantity_available', $difference);

        // Update supplier product data
        $supplierProduct->update([
            'supplier_id' => $request->supplier_id,
            'product_id'  => $request->product_id,
            'quantity'    => $request->quantity,
            'price'       => $request->price,
            'discount'    => $request->discount,
            'price_1'     => $request->price_1,
            'price_2'     => $request->price_2,
            'price_3'     => $request->price_3,
        ]);

        return redirect()->route('admin.suppliers.listProduct')->with('success', 'Product supplier updated successfully.');
    }
    public function history(Request $request)
    {
    $suppliers = Supplier::all();
    $supplierId = $request->supplier_id;

    $supplierProducts = ProductSupplier::with('product')
        ->when($supplierId, function ($query) use ($supplierId) {
            return $query->where('supplier_id', $supplierId);
        })
        ->get();

    return view('admin.suppliers.history', compact('suppliers', 'supplierProducts', 'supplierId'));
    }
    public function exportHistory($supplierId)
    {
    $supplier = Supplier::findOrFail($supplierId);
    $supplierProducts = ProductSupplier::with('product')
        ->where('supplier_id', $supplierId)
        ->get();

    $fileName = "supplier_history_{$supplier->name}.csv";
    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
    ];

    $callback = function () use ($supplierProducts) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ["Product Name", "Product SKU", "Quantity", "Price", "Discount", "Date Added"]);

        foreach ($supplierProducts as $sp) {
            fputcsv($file, [
                $sp->product->name,
                $sp->product->sku,
                $sp->quantity,
                $sp->price,
                $sp->discount,
                $sp->created_at->format('Y-m-d'),
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
    }


}