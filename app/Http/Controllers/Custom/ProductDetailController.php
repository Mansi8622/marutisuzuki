<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductDetailController extends Controller
{
    public function index($id)
    {
        $products = Product::find($id);

        $productss = Product::take(4)->get();
        return view('custom.product-detail', compact('products', 'productss'));
    }
}
