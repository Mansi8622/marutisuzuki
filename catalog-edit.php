<?php
function editFile($path, $callback) { $s = str_replace("\r\n", "\n", file_get_contents($path)); file_put_contents($path, $callback($s)); }
editFile('routes/web.php', fn($s) => str_replace("Route::resource('sub-categories', 'SubCategoryController');", "Route::resource('sub-categories', 'SubCategoryController');\n    Route::resource('vehicles', 'VehicleController')->except('show');", $s));
editFile('resources/views/partials/menu.blade.php', fn($s) => str_replace("@can('sub_category_access')", "@can('sub_category_access')\n<li><a href=\"{{ route('admin.vehicles.index') }}\"><i class=\"fa-fw fas fa-car\"></i><span>Vehicle Name</span></a></li>", $s));
editFile('app/Http/Controllers/Admin/SubCategoryController.php', function($s) {
 $s = str_replace("'is_subcategory' => ! empty(\$data['parent_id'])", "'is_subcategory' => true", $s);
 $s = str_replace("'parent_id' => ['nullable', 'exists:product_categories,id'], ", '', $s);
 return str_replace("->whereNotNull('parent_id')->delete()", "->where('is_subcategory', true)->delete()", $s);
});
foreach (['Store','Update'] as $type) editFile("app/Http/Requests/{$type}ProductCategoryRequest.php", fn($s) => str_replace("'parent_id' => ['nullable', 'exists:product_categories,id'],", <<<'TXT'
'has_subcategories' => ['required', 'boolean'],
            'subcategories' => ['required_if:has_subcategories,1', 'array'],
            'subcategories.*' => ['integer', \Illuminate\Validation\Rule::exists('product_categories', 'id')->where('is_subcategory', true)->whereNull('deleted_at')],
TXT, $s));
editFile('app/Http/Controllers/Admin/ProductCategoryController.php', function($s) {
 $s = str_replace("ProductCategory::with(['media', 'children', 'parent'])->orderBy", "ProductCategory::where('is_subcategory', false)->with(['media', 'subcategories.vehicles', 'parent'])->orderBy", $s);
 $s = str_replace("\$data['is_subcategory'] = ! empty(\$data['parent_id']);", "\$data['is_subcategory'] = false;\n        \$data['parent_id'] = null;", $s);
 $s = str_replace("\$data['has_subcategories'] = false;", "\$data['has_subcategories'] = \$request->boolean('has_subcategories');", $s);
 $s = str_replace("\$productCategory = ProductCategory::create(\$data);", "\$productCategory = ProductCategory::create(\$data);\n        \$productCategory->subcategories()->sync(\$data['has_subcategories'] ? \$request->input('subcategories', []) : []);", $s);
 $s = str_replace("\$productCategory->update(\$data);", "\$productCategory->update(\$data);\n        \$productCategory->subcategories()->sync(\$data['has_subcategories'] ? \$request->input('subcategories', []) : []);", $s);
 return $s;
});
foreach(['create','edit'] as $view) editFile("resources/views/admin/productCategories/$view.blade.php", function($s) {
 $start = strpos($s, '                        <div class="form-group {{ $errors->has(\'parent_id\')');
 $end = strpos($s, '                        <div class="form-group {{ $errors->has(\'photo\')', $start);
 return substr_replace($s, "                        @include('admin.productCategories.companies')\n", $start, $end-$start);
});
foreach(['show','index'] as $view) editFile("resources/views/admin/productCategories/$view.blade.php", fn($s) => str_replace('$productCategory->children', '$productCategory->subcategories', $s));
editFile('resources/views/admin/productCategories/show.blade.php', fn($s) => str_replace('{{ $subCategory->name }}</span>', '{{ $subCategory->name }}</span><p>{{ $subCategory->vehicles->pluck(\'name\')->implode(\', \') }}</p>', $s));
editFile('app/Http/Controllers/Admin/ProductController.php', function($s) {
 $s = preg_replace('/return ProductCategory::with\(\'parent.parent.parent\'\).*?\n        \}\);/s', "return ProductCategory::where('is_subcategory', false)->orderBy('name')->pluck('name', 'id');", $s);
 $s = str_replace("DB::beginTransaction();", "\$fitmentRows = \\App\\Services\\CatalogFitments::validateProduct(\$request);\n        DB::beginTransaction();", $s);
 // Update currently starts its own transaction too; validate before any mutation if needed.
 $needle = 'public function update(UpdateProductRequest $request, Product $product)';
 $pos = strpos($s, $needle); $brace = strpos($s, '{', $pos);
 $s = substr_replace($s, "\n    \$fitmentRows = \\App\\Services\\CatalogFitments::validateProduct(\$request);", $brace+1, 0);
 $s = str_replace("\$product->categories()->sync(\$request->input('categories', []));", "\$product->categories()->sync(\$request->input('categories', []));\n            \$product->fitments()->delete();\n            \$product->fitments()->createMany(\$fitmentRows);", $s);
 return $s;
});
foreach(['create','edit'] as $view) editFile("resources/views/admin/products/$view.blade.php", fn($s) => str_replace('</form>', "@include('admin.products.fitments')\n</form>", $s));
editFile('app/Http/Controllers/Custom/CartController.php', function($s) {
 $s = str_replace("\$rolePrice = \$catalogProduct->sellingPrice();", "\$selection = \\App\\Services\\CatalogFitments::selection(\$catalogProduct, \$request->input('fitment_id'), \$request->input('category_id'));\n    \$cartKey = \$productId . ':' . (\$selection['fitment_id'] ?? 'c'.\$selection['category_id']);\n    \$rolePrice = \$catalogProduct->sellingPrice();", $s);
 $s = str_replace("'name' => \$request->name", "'name' => \$catalogProduct->name", $s);
 $s = str_replace("'description' => \$request->description", "'description' => \$catalogProduct->description", $s);
 $s = str_replace("'gst' => \$request->gst", "'gst' => \$catalogProduct->gst", $s);
 $s = str_replace("// If the product is already", "\$product = array_merge(\$product, \$selection, ['cart_key' => \$cartKey]);\n\n    // If the product is already", $s);
 $start = strpos($s, '// If the product is already'); $end = strpos($s, "session()->put('cart'", $start);
 return substr_replace($s, str_replace('$cart[$productId]', '$cart[$cartKey]', substr($s, $start, $end-$start)), $start, $end-$start);
});
editFile('resources/views/custom/cart.blade.php', fn($s) => str_replace("{{ \$item['id'] }}", "{{ \$item['cart_key'] ?? \$item['id'] }}", str_replace("<h4 class=\"mb-2\">{{ \$item['name'] }}</h4>", "<h4 class=\"mb-2\">{{ \$item['name'] }}</h4>@include('custom.partials.selection', ['selection' => \$item])", $s)));
editFile('resources/views/custom/delivery.blade.php', fn($s) => str_replace("<strong>{{ \$item['name'] }}</strong>", "<strong>{{ \$item['name'] }}</strong>@include('custom.partials.selection', ['selection' => \$item])", $s));
editFile('app/Http/Controllers/Custom/CheckOrderController.php', fn($s) => str_replace("'name' => \$product->name,", "'name' => \$product->name,\n            'selections' => \\App\\Services\\CatalogFitments::orderSelections(\$product, \$quantity),", $s));
editFile('app/Http/Controllers/Frontend/CustomerPaymentController.php', fn($s) => str_replace("'name' => \$product->name,", "'name' => \$product->name, 'selections' => \\App\\Services\\CatalogFitments::orderSelections(\$product, \$quantity),", $s));
foreach(['admin','frontend'] as $area) editFile("resources/views/$area/checkOrders/show.blade.php", fn($s) => str_replace('<div class="panel-body">', "<div class=\"panel-body\">@include('custom.partials.order-selections', ['selectionOrder' => \$checkOrder])", $s));
editFile('resources/views/admin/checkOrders/edit.blade.php', fn($s) => str_replace('<td>{{ $product->name }}</td>', "<td>{{ \$product->name }}@foreach(\$productInfo['selections'] ?? [] as \$selection)@include('custom.partials.selection')@endforeach</td>", $s));
editFile('resources/views/custom/order.blade.php', fn($s) => str_replace('<td>{{ $order->order_number }}</td>', "<td>{{ \$order->order_number }}@include('custom.partials.order-selections', ['selectionOrder' => \$order])</td>", $s));
