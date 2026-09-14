<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\AddCompany;
use App\Models\ProductCategory;

class ProductController extends Controller
{

    public function index()
{
    $products = Product::with(['tags', 'ourStock'])->get();
    return view('custom.product', compact('products'));
}

   public function companyProducts($id)
{
    $company = AddCompany::with(['products.tags', 'products.ourStock'])->findOrFail($id);
    $products = $company->products;

    return view('custom.product', compact('products', 'company'));
}

// In controller method to get products for a category:
public function categoryProducts($id)
{
    // Eager load tags and companies for better performance
    $category = ProductCategory::findOrFail($id);

    // Since Product has belongsToMany categories, use whereHas to filter:
    $products = Product::with(['tags', 'companies', 'ourStock'])
                ->whereHas('categories', function($query) use ($id) {
                    $query->where('product_category_id', $id); 
                    // or 'id' if pivot column is different, check your pivot table
                })
                ->get();

    return view('custom.product', compact('products', 'category'));
}

}
