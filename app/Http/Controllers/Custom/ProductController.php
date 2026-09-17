<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\AddCompany;
use App\Models\ProductCategory;

class ProductController extends Controller
{

    public function index(Request $request)
{
    $products = $this->applyPriceFilter(Product::with(['tags', 'ourStock', 'categories']), $request)->get();
    $categories = ProductCategory::with('subcategories.vehicles')->where('is_subcategory', false)->orderBy('name')->get();
    $browseCategories = $categories;
    $browseLevel = 'category';
    $products = collect();
    return view('custom.product', compact('products', 'categories', 'browseCategories', 'browseLevel'));
}

   public function companyProducts($id)
{
    $company = AddCompany::with(['products.tags', 'products.ourStock'])->findOrFail($id);
    $products = $company->products;

    return view('custom.product', compact('products', 'company'));
}

// In controller method to get products for a category:
public function categoryProducts(Request $request, $id)
{
    $category = ProductCategory::where('is_subcategory', false)->with('subcategories.vehicles')->findOrFail($id);
    $selectedCategory = $category;
    $selectedCompany = null;
    $selectedVehicle = null;
    $browseLevel = 'company';
    $browseCategories = $category->has_subcategories ? $category->subcategories : collect();
    if ($request->filled('subcategory')) {
        $selectedCompany = $category->subcategories()->findOrFail($request->input('subcategory'));
        abort_unless($category->has_subcategories, 404);
        $browseCategories = $selectedCompany->vehicles;
        $browseLevel = 'vehicle';
        if ($request->filled('vehicle')) {
            $selectedVehicle = $selectedCompany->vehicles()->findOrFail($request->input('vehicle'));
            $browseCategories = collect();
            $browseLevel = 'product';
        }
    } else {
        abort_if($request->filled('vehicle'), 404);
    }
    $products = collect();

    // Categories with children are navigation levels (company/model cards).
    // Products only appear at the final vehicle-model category.
    if (!$category->has_subcategories || $selectedVehicle) {
        $products = $this->applyPriceFilter(
            Product::with(['tags', 'companies', 'ourStock', 'categories'])
                ->whereHas('categories', fn ($query) => $query->where('product_category_id', $category->id))
                ->when($selectedVehicle, fn ($query) => $query->whereHas('fitments', fn ($q) => $q->where('category_id', $category->id)->where('vehicle_id', $selectedVehicle->id))),
            $request
        )->get();
    }

    $categories = ProductCategory::with('subcategories.vehicles')->where('is_subcategory', false)->orderBy('name')->get();
    return view('custom.product', compact('products', 'category', 'categories', 'browseCategories', 'browseLevel', 'selectedCategory', 'selectedCompany', 'selectedVehicle'));
}

private function applyPriceFilter($query, Request $request)
{
    $request->validate([
        'min_price' => ['nullable', 'numeric', 'min:0'],
        'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
    ]);

    // The price column used on the storefront depends on the signed-in role.
    $priceColumn = auth('customer')->check() ? 'rate_2' : (auth('web')->check() ? 'price_1' : 'price');
    if ($request->filled('min_price')) $query->where($priceColumn, '>=', $request->min_price);
    if ($request->filled('max_price')) $query->where($priceColumn, '<=', $request->max_price);
    return $query;
}

}
