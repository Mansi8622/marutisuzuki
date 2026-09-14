<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    /**
     * Lightweight type-ahead search used by the storefront header.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        if (mb_strlen($term) < 3) {
            return response()->json(['products' => []]);
        }

        $products = Product::query()
            ->with(['categories:id,name', 'companies:id,company_name', 'media'])
            ->where(function ($query) use ($term) {
                $like = '%' . $term . '%';

                $query->where('name', 'like', $like)
                    ->orWhere('item_code', 'like', $like)
                    ->orWhere('sku', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('price', 'like', $like)
                    ->orWhere('price_1', 'like', $like)
                    ->orWhereHas('categories', fn ($category) => $category->where('name', 'like', $like))
                    ->orWhereHas('companies', fn ($company) => $company->where('company_name', 'like', $like));
            })
            ->orderBy('name')
            ->limit(8)
            ->get()
            ->map(function (Product $product) {
                $price = (float) ($product->price ?? 0);
                $discount = (float) ($product->discount ?? 0);
                $finalPrice = $price - ($price * $discount / 100);
                $photo = $product->getMedia('photo')->first();

                return [
                    'name'       => $product->name,
                    'item_code'  => $product->item_code ?: $product->sku,
                    'price'      => number_format($finalPrice, 2),
                    'has_discount' => $discount > 0,
                    'image'      => $photo ? $photo->getUrl('preview') : null,
                    'categories' => $product->categories->pluck('name')->values(),
                    'companies'  => $product->companies->pluck('company_name')->values(),
                    'url'        => route('custom.product-detail', $product->id),
                ];
            })
            ->values();

        return response()->json(['products' => $products]);
    }
}
