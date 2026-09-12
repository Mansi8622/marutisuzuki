<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\AddCompany;

class HomeController extends Controller
{
    public function index()
    {
$allproducts = AddCompany::latest()->take(4)->get();

        $newproducts = Product::take(6)->get();

        $trendingproducts = Product::take(6)->get();

        $exploreproducts = Product::take(6)->get();
        return view('custom.index', compact('allproducts', 'newproducts', 'trendingproducts', 'exploreproducts'));
    }
}
