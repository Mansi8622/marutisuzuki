<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\AddCompany;
use App\Models\OurStock;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Godown; 

class ProductController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::with(['categories', 'tags', 'select_companies', 'created_by', 'media'])->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        $categories = ProductCategory::pluck('name', 'id');
        $tags = ProductTag::pluck('name', 'id');
        $select_companies = AddCompany::pluck('company_name', 'id');
        $godowns = Godown::pluck('name', 'id'); // 👈 Add this line
    
        return view('admin.products.create', compact('categories', 'select_companies', 'tags', 'godowns'));
    }

    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        try {
            // Generate a unique SKU
            $sku = $this->generateUniqueSKU();

            // Add the SKU to the request data
            $productData = $request->all();
            $productData['sku'] = $sku;

            // Store product in the database
            $product = Product::create($productData);

            // Sync related data
            $product->categories()->sync($request->input('categories', []));
            $product->tags()->sync($request->input('tags', []));
            $product->select_companies()->sync($request->input('select_companies', []));

            // Handle product photos
            foreach ($request->input('photo', []) as $file) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo');
            }

            foreach ($request->input('product_photo_2', []) as $file) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('product_photo_2');
            }

            if ($request->input('product_photo_3', false)) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('product_photo_3'))))->toMediaCollection('product_photo_3');
            }

            if ($media = $request->input('ck-media', false)) {
                Media::whereIn('id', $media)->update(['model_id' => $product->id]);
            }

            // Add product data to OurStock table, including quantity
            $this->addProductToOurStock($product);

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load('categories', 'tags', 'select_companies', 'created_by', 'selectProductOurStocks', 'selectProductCheckGodowns', 'productCancellations', 'productRefunds', 'selectProductCheckOrders');

        return view('admin.products.show', compact('product'));
    }

    private function generateUniqueSKU()
    {
        do {
            $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4));
            $numbers = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $sku = $letters . $numbers;
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

    private function addProductToOurStock($product)
    {
        OurStock::create([
            'select_product_id' => $product->id,
            'sku'               => $product->sku,
            'quantity_available'          => $product->quantity, // Save product quantity
           
        ]);
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::pluck('name', 'id');
        $tags = ProductTag::pluck('name', 'id');
        $select_companies = AddCompany::pluck('company_name', 'id');

        $product->load('categories', 'tags', 'select_companies', 'created_by');

        return view('admin.products.edit', compact('categories', 'product', 'select_companies', 'tags'));
    }

  public function update(UpdateProductRequest $request, Product $product)
{
    DB::beginTransaction();

    try {
        // Update basic product data
        $product->update($request->all());

        // Sync relations
        $product->categories()->sync($request->input('categories', []));
        $product->tags()->sync($request->input('tags', []));
        $product->select_companies()->sync($request->input('select_companies', []));

        // ✅ Handle updated media uploads from tmp folder (like in store method)

        // photo
        if ($request->input('photo', false)) {
            $product->clearMediaCollection('photo');
            foreach ($request->input('photo', []) as $file) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo');
            }
        }

        // product_photo_2
        if ($request->input('product_photo_2', false)) {
            $product->clearMediaCollection('product_photo_2');
            foreach ($request->input('product_photo_2', []) as $file) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('product_photo_2');
            }
        }

        // product_photo_3 (single)
        if ($request->input('product_photo_3', false)) {
            $product->clearMediaCollection('product_photo_3');
            $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('product_photo_3'))))->toMediaCollection('product_photo_3');
        }

        // Update ck-media if used
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $product->id]);
        }

        // Update stock
        $this->updateProductInOurStock($product);

        DB::commit();

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

    private function updateProductInOurStock($product)
    {
        $stock = OurStock::where('select_product_id', $product->id)->first();
        if ($stock) {
            $stock->update([
                'select_product_id' => $product->id,
                'sku'               => $product->sku,
                'quantity_available' => $product->quantity, // Save product quantity
               
              
            ]);
        }
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();

        try {
            // Delete from OurStock table
            OurStock::where('select_product_id', $product->id)->delete();

            $product->delete();

            DB::commit();

            return back()->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        DB::beginTransaction();

        try {
            $products = Product::find(request('ids'));

            foreach ($products as $product) {
                OurStock::where('select_product_id', $product->id)->delete();
                $product->delete();
            }

            DB::commit();

            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
