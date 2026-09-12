<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function getProducts(Request $request, $id = null)
{
    $relations = [
        'categories',
        'tags',
        'select_companies',
        'created_by',
        'media',
        'godown' // if relation exists
    ];

    // 🟦 Case 1: All products
    if ($id === null) {
        $products = Product::with($relations)->get();

        $cleanData = $products->map(function ($product) {
            return [
                "id"          => $product->id,
                "name"        => $product->name,
                "item_code"   => $product->item_code,
                "hsn_code"    => $product->hsn_code,
                "description" => $product->description,

                // ⭐ Prices
                "price_main"  => $product->price,
                "price_1"     => $product->price_1,
                "price_2"     => $product->rate_2,
                "price_3"     => $product->rate_3,

                "sku"         => $product->sku,
                "quantity"    => $product->quantity,

                // ⭐ Godown
                "godown_id"   => $product->godown_id,
                "godown_name" => $product->godown->name ?? null,

                // ⭐ Images
                "image1_url"  => $product->getFirstMediaUrl('photo'),
                "image2_url"  => $product->getFirstMediaUrl('product_photo_2'),
                "image3_url"  => $product->getFirstMediaUrl('product_photo_3'),

                // ⭐ Simple names only
                "categories"  => $product->categories->pluck('name'),
                "companies"   => $product->select_companies->pluck('company_name'),
                "tags"        => $product->tags->pluck('name'),
            ];
        });

        return response()->json([
            'status'  => true,
            'message' => 'All products fetched successfully',
            'data'    => $cleanData,
        ], 200);
    }

    // 🟩 Case 2: Single product
    $product = Product::with($relations)->find($id);

    if (!$product) {
        return response()->json([
            'status'  => false,
            'message' => 'Product not found',
        ], 404);
    }

    $cleanSingle = [
        "id"          => $product->id,
        "name"        => $product->name,
        "item_code"   => $product->item_code,
        "hsn_code"    => $product->hsn_code,
        "description" => $product->description,

        // ⭐ Prices
        "price_main"  => $product->price,
        "price_1"     => $product->price_1,
        "price_2"     => $product->rate_2,
        "price_3"     => $product->rate_3,

        "sku"         => $product->sku,
        "quantity"    => $product->quantity,

        // ⭐ Godown
        "godown_id"   => $product->godown_id,
        "godown_name" => $product->godown->name ?? null,

        // ⭐ Images
        "image1_url"  => $product->getFirstMediaUrl('photo'),
        "image2_url"  => $product->getFirstMediaUrl('product_photo_2'),
        "image3_url"  => $product->getFirstMediaUrl('product_photo_3'),

        // ⭐ Names only
        "categories"  => $product->categories->pluck('name'),
        "companies"   => $product->select_companies->pluck('company_name'),
        "tags"        => $product->tags->pluck('name'),
    ];

    return response()->json([
        'status'  => true,
        'message' => 'Product details fetched successfully',
        'data'    => $cleanSingle,
    ], 200);
}



}
