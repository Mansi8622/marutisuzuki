<?php
function editFile($path, $callback) { $s = str_replace("\r\n", "\n", file_get_contents($path)); file_put_contents($path, $callback($s)); }
foreach(['resources/views/custom/master.blade.php','resources/views/custom/product.blade.php'] as $path) editFile($path, function($s) {
 $s = str_replace("with('children')->whereNull('parent_id')", "with('subcategories.vehicles')->where('is_subcategory', false)", $s);
 $s = str_replace('$category->children', '$category->subcategories', $s);
 $s = str_replace("route('category.products', \$subCategory->id)", "route('category.products', ['id' => \$category->id, 'subcategory' => \$subCategory->id])", $s);
 return $s;
});
editFile('resources/views/custom/master.blade.php', function($s) {
 $s = str_replace('<li><a href="{{ route(\'category.products\', [\'id\' => $category->id, \'subcategory\' => $subCategory->id]) }}"', '<li class="company-with-vehicles"><a href="{{ route(\'category.products\', [\'id\' => $category->id, \'subcategory\' => $subCategory->id]) }}"', $s);
 $s = str_replace('{{ $subCategory->name }}</a></li>', <<<'TXT'
{{ $subCategory->name }}</a><ul class="vehicle-submenu list-unstyled ms-3">
@foreach($subCategory->vehicles as $navVehicle)<li><a class="dropdown-item small" href="{{ route('category.products', ['id' => $category->id, 'subcategory' => $subCategory->id, 'vehicle' => $navVehicle->id]) }}">{{ $navVehicle->name }}</a></li>@endforeach
</ul></li>
TXT, $s);
 return str_replace('.header3 .category-submenu{', '.vehicle-submenu{display:none}.company-with-vehicles:hover>.vehicle-submenu,.company-with-vehicles:focus-within>.vehicle-submenu{display:block}.header3 .category-submenu{', $s);
});
editFile('resources/views/custom/product.blade.php', function($s) {
 $s = str_replace("isset(\$browseCategories) && \$browseCategories->isNotEmpty() ? \$category->name : 'Products'", "\$selectedCategory->name ?? 'Categories'", $s);
 $s = str_replace("{{ route('category.products', \$childCategory->id) }}", <<<'TXT'
{{ ($browseLevel ?? 'category') === 'category' ? route('category.products', $childCategory->id) : route('category.products', array_filter(['id' => $selectedCategory->id, 'subcategory' => $selectedCompany->id ?? $childCategory->id, 'vehicle' => ($browseLevel ?? '') === 'vehicle' ? $childCategory->id : null])) }}
TXT, $s);
 $s = str_replace("{{ \$childCategory->children->count() ? 'View models' : 'View products' }}", "{{ (\$browseLevel ?? '') === 'company' ? 'View vehicles' : 'View products' }}", $s);
 $s = str_replace('href="/product-detail/{{ $product->id }}"', 'href="{{ url(\'product-detail/\'.$product->id).\'?\'.http_build_query([\'category\' => $selectedCategory->id ?? null, \'subcategory\' => $selectedCompany->id ?? null, \'vehicle\' => $selectedVehicle->id ?? null]) }}"', $s);
 $s = str_replace('<input type="hidden" name="id" value="{{ $product->id }}">', <<<'TXT'
<input type="hidden" name="id" value="{{ $product->id }}">
<input type="hidden" name="category_id" value="{{ $selectedCategory->id ?? '' }}">
<input type="hidden" name="fitment_id" value="{{ isset($selectedVehicle) ? optional($product->fitments->first(fn($f) => $f->category_id == $selectedCategory->id && $f->vehicle_id == $selectedVehicle->id))->id : '' }}">
TXT, $s);
 $s = str_replace('<button type="submit" class="msv-add-cart">', "@if(isset(\$selectedCategory) && (!\$selectedCategory->has_subcategories || isset(\$selectedVehicle)))<button type=\"submit\" class=\"msv-add-cart\">", $s);
 $s = str_replace("</button>\n                                    </form>", "</button>@else<a class=\"msv-add-cart text-center\" href=\"{{ url('product-detail/'.\$product->id) }}\">Select vehicle</a>@endif\n                                    </form>", $s);
 $s = str_replace('<div class="col-12 text-center">', <<<'TXT'
<div class="col-12 text-center">
@if(isset($selectedCategory))<p><a href="{{ route('custom.product') }}">Categories</a> / <a href="{{ route('category.products', $selectedCategory->id) }}">{{ $selectedCategory->name }}</a>@if(isset($selectedCompany)) / <a href="{{ route('category.products', ['id'=>$selectedCategory->id, 'subcategory'=>$selectedCompany->id]) }}">{{ $selectedCompany->name }}</a>@endif @if(isset($selectedVehicle)) / {{ $selectedVehicle->name }}@endif</p>@endif
TXT, $s);
 $s = str_replace('<form method="GET" action=', '<form method="GET" action=', $s);
 $s = str_replace('class="row g-2">', 'class="row g-2"><input type="hidden" name="subcategory" value="{{ request(\'subcategory\') }}"><input type="hidden" name="vehicle" value="{{ request(\'vehicle\') }}">', $s);
 // Sidebar foreach must not overwrite the page category.
 $start = strpos($s, '@foreach($categories as $category)'); $end = strpos($s, '</ul>', $start);
 if($start !== false) $s = substr_replace($s, str_replace('$category', '$sidebarCategory', substr($s, $start, $end-$start)), $start, $end-$start);
 return $s;
});
editFile('resources/views/custom/product-detail.blade.php', fn($s) => str_replace('<h3 class="pd-title text-capitalize">{{ $products->name }}</h3>', '<h3 class="pd-title text-capitalize">{{ $products->name }}</h3>@include(\'custom.partials.product-selection\', [\'selectionProduct\' => $products])', $s));
editFile('app/Http/Controllers/Admin/ProductController.php', fn($s) => str_replace("\$fitmentRows = \\App\\Services\\CatalogFitments::validateProduct(\$request);\n    \$fitmentRows = \\App\\Services\\CatalogFitments::validateProduct(\$request);", "\$fitmentRows = \\App\\Services\\CatalogFitments::validateProduct(\$request);", $s));
