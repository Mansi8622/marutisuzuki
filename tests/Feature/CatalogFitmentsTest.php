<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Vehicle;
use App\Services\CatalogFitments;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CatalogFitmentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('product_categories', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->boolean('is_subcategory')->default(false); $t->boolean('has_subcategories')->default(false); $t->timestamps(); $t->softDeletes();
        });
        Schema::create('products', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->decimal('price')->default(100); $t->timestamps(); $t->softDeletes();
        });
        Schema::create('product_product_category', function (Blueprint $t) { $t->unsignedBigInteger('product_id'); $t->unsignedBigInteger('product_category_id'); });
        (require database_path('migrations/2026_09_15_000007_create_vehicle_fitments.php'))->up();
    }

    private function catalogue(): array
    {
        $toyota = ProductCategory::create(['name' => 'Toyota', 'is_subcategory' => true]);
        $suzuki = ProductCategory::create(['name' => 'Suzuki', 'is_subcategory' => true]);
        $category = ProductCategory::create(['name' => 'Door Visor', 'has_subcategories' => true]);
        $category->subcategories()->sync([$toyota->id, $suzuki->id]);
        $fortuner = Vehicle::create(['name' => 'Fortuner', 'subcategory_id' => $toyota->id]);
        $swift = Vehicle::create(['name' => 'Swift', 'subcategory_id' => $suzuki->id]);
        $product = Product::create(['name' => 'Door Visor Set']);
        $product->categories()->sync([$category->id]);
        return [$category, $toyota, $suzuki, $fortuner, $swift, $product];
    }

    public function test_company_is_reusable_and_product_can_exclude_a_vehicle(): void
    {
        [$category, $toyota, , $fortuner, $swift, $product] = $this->catalogue();
        $other = ProductCategory::create(['name' => 'Floor Mat', 'has_subcategories' => true]);
        $other->subcategories()->sync([$toyota->id]);
        $rows = CatalogFitments::validateProduct(Request::create('/', 'POST', ['categories' => [$category->id], 'fitments' => [$category->id.':'.$fortuner->id]]));
        $product->fitments()->createMany($rows);
        $this->assertCount(1, $product->availableFitments());
        $this->assertEquals($fortuner->id, $product->availableFitments()->first()->vehicle_id);
        $this->assertEquals($fortuner->id, $other->subcategories->first()->vehicles->first()->id);
    }

    public function test_unrelated_vehicle_is_rejected(): void
    {
        [$category, , , , , $product] = $this->catalogue();
        $this->expectException(ValidationException::class);
        CatalogFitments::validateProduct(Request::create('/', 'POST', ['categories' => [$category->id], 'fitments' => [$category->id.':999']]));
    }

    public function test_two_vehicle_cart_lines_are_separate_and_order_keeps_both(): void
    {
        [$category, , , $fortuner, $swift, $product] = $this->catalogue();
        $first = $product->fitments()->create(['category_id' => $category->id, 'vehicle_id' => $fortuner->id]);
        $second = $product->fitments()->create(['category_id' => $category->id, 'vehicle_id' => $swift->id]);
        $cart = new \App\Http\Controllers\Custom\CartController;
        foreach ([$first, $second, $first] as $fitment) $cart->addToCart(Request::create('/', 'POST', ['id' => $product->id, 'fitment_id' => $fitment->id]));
        $this->assertCount(2, session('cart'));
        $selections = CatalogFitments::orderSelections($product, 3);
        $this->assertSame(['Fortuner', 'Swift'], array_column($selections, 'vehicle_name'));
        $this->assertSame([2, 1], array_column($selections, 'quantity'));
        $fortuner->update(['name' => 'Renamed']);
        $this->assertSame('Fortuner', $selections[0]['vehicle_name']);
    }

    public function test_removed_company_cannot_be_added_to_cart(): void
    {
        [$category, , , $fortuner, , $product] = $this->catalogue();
        $fitment = $product->fitments()->create(['category_id' => $category->id, 'vehicle_id' => $fortuner->id]);
        $category->subcategories()->detach();
        $this->expectException(ValidationException::class);
        CatalogFitments::selection($product, $fitment->id);
    }

    public function test_browsing_keeps_category_company_and_vehicle_context(): void
    {
        [$category, $toyota, $suzuki, $fortuner] = $this->catalogue();
        $controller = new \App\Http\Controllers\Custom\ProductController;
        $view = $controller->categoryProducts(Request::create('/', 'GET'), $category->id);
        $this->assertSame('company', $view->getData()['browseLevel']);
        $this->assertCount(2, $view->getData()['browseCategories']);
        $view = $controller->categoryProducts(Request::create('/', 'GET', ['subcategory' => $toyota->id]), $category->id);
        $this->assertSame('vehicle', $view->getData()['browseLevel']);
        $this->assertEquals($fortuner->id, $view->getData()['browseCategories']->first()->id);
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $controller->categoryProducts(Request::create('/', 'GET', ['subcategory' => $suzuki->id, 'vehicle' => $fortuner->id]), $category->id);
    }
}
