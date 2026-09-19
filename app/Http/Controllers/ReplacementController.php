<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckOrder;
use App\Models\Replacement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AddCompany;
use Dompdf\Dompdf;
use Dompdf\Options;

class ReplacementController extends Controller
{

    public function index()
    {
        $userId = Auth::guard('web')->check() ? Auth::id() : null;
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;

        // Logged-in user ke hisaab se orders fetch karenge
        if (Auth::guard('web')->check()) {
            $orders = CheckOrder::where('select_user_id', Auth::id())->get();
        } elseif (Auth::guard('customer')->check()) {
            $orders = CheckOrder::where('select_customer_id', Auth::guard('customer')->id())->get();
        } else {
            $orders = collect();
        }

        $replacements = Replacement::with(['company', 'product', 'checkOrder'])
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->when($customerId, fn ($query) => $query->where('customer_id', $customerId))
            ->latest()->get();

        return view('custom.replacement', compact('orders', 'replacements'));
    }

    public function getOrderDetails($id)
    {
        // Fetch the order with associated products and their companies
        $order = CheckOrder::with(['select_products.companies'])->find($id);
    
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    
        return response()->json([
            'order' => $order,
            'products' => $order->select_products->map(function ($product) {
                // Get the first company (if multiple companies exist)
                $company = $product->companies->first();
    
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $product->pivot->quantity ?? 0,
                    'company_id' => $company ? $company->id : null,
                    'company_name' => $company ? $company->company_name : null,
                    'companies' => $product->companies ? $product->companies->pluck('company_name') : [],
                ];
            }),
        ]);
    }
    
    
        
    public function store(Request $request)
{
    
    // Validate input
    $request->validate([
        'selected_products' => 'required|array',
        'selected_products.*.product_id' => 'exists:products,id',
        'selected_products.*.company_id' => 'required|exists:add_companies,id',
       
        'check_order_id' => 'required|exists:check_orders,id',
        'order_number' => 'required|string',
        'issues' => 'nullable|string',
        'notes' => 'nullable|string',
        'customer_name' => 'required|string',
        'customer_email' => 'required|string',
        'customer_phone' => 'required|string',
        'customer_notes' => 'nullable|string',
        'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
    ]);

    $filePath = null;
    if ($request->hasFile('attachment')) {
        $filePath = $request->file('attachment')->store('attachments', 'public');
    }

    $userId = Auth::guard('web')->check() ? Auth::id() : null;
    $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null;

    $alreadyRequested = [];

    foreach ($request->selected_products as $product) {
        if (!isset($product['product_id']) || !isset($product['quantity'])) {
            continue;
        }
    

        // Check if replacement already exists for the same order and product
        $existing = Replacement::where('check_order_id', $request->check_order_id)
            ->where('product_id', $product['product_id'])
            ->latest()
            ->first();

        if ($existing && $existing->status !== 'Confirmed') {
            // Collect details to show in popup
            $alreadyRequested[] = [
                'product_id' => $product['product_id'],
                'status' => $existing->status,
            ];
            continue;
        }

        // Create new replacement
        Replacement::create([
            'product_id' => (int) $product['product_id'],
            'check_order_id' => (int) $request->check_order_id,
            

            'quantity' => (int) $product['quantity'],
            
            'order_number' => (string) $request->order_number,
            'company_id' => (int) $product['company_id'],
            'issues' => $request->issues ?? null,
            'notes' => $request->notes ?? null,
            'user_id' => $userId,
            'customer_id' => $customerId,
            'customer_name' => (string) $request->customer_name,
            'customer_email' => (string) $request->customer_email,
            'customer_phone' => (string) $request->customer_phone,
            'customer_notes' => $request->customer_notes ?? null,
            'attachment' => $filePath ?? null,
        ]);
    
    }

    

    

    // If any product was already requested, return with a message
    if (!empty($alreadyRequested)) {
        $message = "This products already have replacement requests:<br>";
    
        foreach ($alreadyRequested as $item) {
            $product = \App\Models\Product::find($item['product_id']);
            $productName = $product ? $product->name : 'Unknown Product';
            $message .= "Product: <strong>{$productName}</strong> — Status: <strong>{$item['status']}</strong><br>";
        }
    
        return redirect()->back()->with('error', $message);
    }
    

    return redirect()->route('frontend.replacements.index')->with('success', 'Replacement request created successfully.');
}

    


    public function company()
    {
        $user = Auth::user();

        if (!$user || !$user->roles()->where('title', 'Company')->exists()) {
            return abort(403, 'Unauthorized access');
        }
    
        $company = AddCompany::where('company_name', $user->business_name)->first();
    
        if (!$company) {
            return abort(403, 'Company not found');
        }
    
        $replacements = Replacement::where('company_id', $company->id)
            ->with([
                'checkOrders.products',
                'user',
                'customer'
            ])
            ->get();
    
        return view('company.index', compact('replacements'));
    
    }

    public function show($id)
    {
        $user = Auth::user();

        if (!$user || !$user->roles()->where('title', 'Company')->exists()) {
            return abort(403, 'Unauthorized access');
        }
    
        $company = AddCompany::where('company_name', $user->business_name)->first();
    
        if (!$company) {
            return abort(403, 'Company not found');
        }
        $replacement = Replacement::with(['checkOrder.products', 'user', 'customer', 'company'])->findOrFail($id);
        return view('company.show', compact('replacement'));
    }

    public function edit($id)
    {
        $user = Auth::user();

        if (!$user || !$user->roles()->where('title', 'Company')->exists()) {
            return abort(403, 'Unauthorized access');
        }
    
        $company = AddCompany::where('company_name', $user->business_name)->first();
    
        if (!$company) {
            return abort(403, 'Company not found');
        }
        $replacement = Replacement::findOrFail($id);
        return view('company.edit', compact('replacement'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
    
        // Check if the user is authenticated and has the "Company" role
        if (!$user || !$user->roles()->where('title', 'Company')->exists()) {
            return abort(403, 'Unauthorized access');
        }
    
        // Verify if the company exists for the logged-in user
        $company = AddCompany::where('company_name', $user->business_name)->first();
        if (!$company) {
            return abort(403, 'Company not found');
        }
    
        // Find the replacement record
        $replacement = Replacement::findOrFail($id);
    
        // Check if the status is already "Approved"
        if ($replacement->status === 'Approved') {
            return back()->with('error', 'You cannot modify an Approved replacement.');
        }
    
        // Validate request data
        $request->validate([
            'status' => 'required|string',
            'info' => 'nullable|string',
        ]);
    
        // Update the replacement record
        $replacement->status = $request->status;
        $replacement->info = $request->info;
        $replacement->save();
    
        return redirect()->route('frontend.replacements.company')->with('success', 'Replacement updated successfully.');
    }
    

    public function destroy($id)
    {
        $user = Auth::user();

        if (!$user || !$user->roles()->where('title', 'Company')->exists()) {
            return abort(403, 'Unauthorized access');
        }
    
        $company = AddCompany::where('company_name', $user->business_name)->first();
    
        if (!$company) {
            return abort(403, 'Company not found');
        }
        $replacement = Replacement::findOrFail($id);
        $replacement->delete();
        return redirect()->route('frontend.replacements.company')->with('success', 'Replacement deleted successfully.');
    }
    
    
    public function generateReplacementInvoice($id)
    {
        $replacement = Replacement::with(['company', 'product', 'checkOrder', 'user', 'customer'])->findOrFail($id);
        $this->ensureReplacementOwner($replacement);
    
        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isPhpEnabled', true);
    
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml(view('custom.replacement-invoice', compact('replacement'))->render());
    
        // (Optional) Set paper size
        $dompdf->setPaper('A4', 'portrait');
    
        // Render the PDF (first pass)
        $dompdf->render();
    
        // Stream the generated PDF (force download)
        return $dompdf->stream('replacement-invoice.pdf', ['Attachment' => 1]);
    }

    public function downloadInvoice($id)
    {
        return $this->generateReplacementInvoice($id);
    }

    public function customerShow(Replacement $replacement)
    {
        $this->ensureReplacementOwner($replacement);
        return view('custom.replacement-show', compact('replacement'));
    }

    public function customerEdit(Replacement $replacement)
    {
        $this->ensureReplacementOwner($replacement);
        abort_unless(strtolower($replacement->status ?? 'pending') === 'pending', 403, 'Only pending replacement requests can be updated.');
        return view('custom.replacement-edit', compact('replacement'));
    }

    public function customerUpdate(Request $request, Replacement $replacement)
    {
        $this->ensureReplacementOwner($replacement);
        abort_unless(strtolower($replacement->status ?? 'pending') === 'pending', 403, 'Only pending replacement requests can be updated.');
        $data = $request->validate(['customer_name' => ['required','string','max:120'], 'customer_phone' => ['required','string','max:30'], 'customer_email' => ['nullable','email','max:160'], 'customer_notes' => ['nullable','string','max:1000']]);
        $replacement->update($data);
        return redirect()->route('frontend.customer-replacements.show', $replacement)->with('success', 'Replacement contact details updated. Product and quantity were not changed.');
    }

    private function ensureReplacementOwner(Replacement $replacement): void
    {
        $allowed = (Auth::guard('web')->check() && (int) $replacement->user_id === (int) Auth::guard('web')->id())
            || (Auth::guard('customer')->check() && (int) $replacement->customer_id === (int) Auth::guard('customer')->id());

        if (! $allowed && Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user && $user->roles()->where('title', 'Company')->exists()) {
                $company = AddCompany::where('company_name', $user->business_name)->first();
                $allowed = $company && (int) $replacement->company_id === (int) $company->id;
            }
        }

        abort_unless($allowed, 403);
    }
    
    
    
}
