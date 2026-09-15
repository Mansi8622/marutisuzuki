<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CatalogFitments
{
    public static function validateProduct(Request $request): array
    {
        $request->validate(['categories' => 'required|array|min:1', 'categories.*' => 'integer', 'fitments' => 'nullable|array', 'fitments.*' => 'string']);
        $categories = ProductCategory::where('is_subcategory', false)->whereIn('id', $request->input('categories', []))->with('subcategories.vehicles')->get();
        if ($categories->count() !== count(array_unique($request->input('categories', [])))) {
            throw ValidationException::withMessages(['categories' => 'Select valid main categories.']);
        }
        $allowed = [];
        foreach ($categories as $category) {
            if (!$category->has_subcategories) continue;
            foreach ($category->subcategories as $company) {
                foreach ($company->vehicles as $vehicle) $allowed[$category->id.':'.$vehicle->id] = ['category_id' => $category->id, 'vehicle_id' => $vehicle->id];
            }
            if (!collect($request->input('fitments', []))->contains(fn ($key) => isset($allowed[$key]) && $allowed[$key]['category_id'] === $category->id)) {
                throw ValidationException::withMessages(['fitments' => 'Select at least one available vehicle for '.$category->name.'.']);
            }
        }
        $selected = array_unique($request->input('fitments', []));
        if (array_diff($selected, array_keys($allowed))) throw ValidationException::withMessages(['fitments' => 'A selected vehicle does not belong to this category.']);
        return array_values(array_intersect_key($allowed, array_flip($selected)));
    }

    public static function selection(Product $product, $fitmentId, $categoryId = null): array
    {
        if ($fitmentId) {
            $fitment = $product->availableFitments()->firstWhere('id', (int) $fitmentId);
            if (!$fitment) throw ValidationException::withMessages(['fitment_id' => 'This vehicle is no longer available for this product.']);
            return $fitment->snapshot();
        }
        $category = $product->categories->where('is_subcategory', false)->where('has_subcategories', false)->firstWhere('id', (int) $categoryId);
        if (!$category && !$categoryId && $product->categories->count() === 1 && !$product->categories->first()->has_subcategories) $category = $product->categories->first();
        if (!$category) throw ValidationException::withMessages(['fitment_id' => 'Please select a category, company and vehicle on the product page.']);
        return ['category_id' => $category->id, 'category_name' => $category->name];
    }

    public static function orderSelections(Product $product, int $quantity): array
    {
        $lines = collect(session('cart', []))->filter(fn ($line) => (int) ($line['id'] ?? 0) === $product->id);
        if ($lines->sum('quantity') !== $quantity) throw ValidationException::withMessages(['products' => 'Your cart changed. Please review it before ordering.']);
        return $lines->map(function ($line) use ($product) {
            return self::selection($product, $line['fitment_id'] ?? null, $line['category_id'] ?? null) + ['quantity' => (int) $line['quantity']];
        })->values()->all();
    }
}
