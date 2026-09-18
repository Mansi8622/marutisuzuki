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
    
        $categories = $this->hierarchicalCategories();
        $tags = ProductTag::pluck('name', 'id');
        $select_companies = AddCompany::pluck('company_name', 'id');
        $godowns = Godown::pluck('name', 'id'); // 👈 Add this line
    
        return view('admin.products.create', compact('categories', 'select_companies', 'tags', 'godowns'));
    }

    public function store(StoreProductRequest $request)
    {
        $fitmentRows = \App\Services\CatalogFitments::validateProduct($request);
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
            $product->fitments()->delete();
            $product->fitments()->createMany($fitmentRows);
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

            // 🎁 Save FOC / scheme slabs (Slab 1: buy X get Y free, etc.)
            if ($request->has('foc_slabs')) {
                foreach ($request->input('foc_slabs', []) as $slab) {
                    if (!empty($slab['slab_name']) && !empty($slab['buy_qty']) && !empty($slab['free_qty'])) {
                        $product->focSlabs()->create($slab);
                    }
                }
            }

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
    abort_if(
        Gate::denies('product_edit'),
        Response::HTTP_FORBIDDEN,
        '403 Forbidden'
    );

    $categories = $this->hierarchicalCategories();

    $tags = ProductTag::pluck('name', 'id');

    $select_companies = AddCompany::pluck(
        'company_name',
        'id'
    );

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT: Load Godowns
    |--------------------------------------------------------------------------
    */

    $godowns = Godown::orderBy('name')
        ->pluck('name', 'id');


    /*
    |--------------------------------------------------------------------------
    | Load Product Relations
    |--------------------------------------------------------------------------
    */

    $product->load([
        'categories',
        'tags',
        'select_companies',
        'created_by',
        'focSlabs',
        'media',
    ]);


    return view(
        'admin.products.edit',
        compact(
            'categories',
            'product',
            'select_companies',
            'tags',
            'godowns'
        )
    );
}

public function update(
    UpdateProductRequest $request,
    Product $product
) {
    $fitmentRows =
        \App\Services\CatalogFitments::validateProduct($request);

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | 1. Product Basic Data
        |--------------------------------------------------------------------------
        */

        $product->update([
            'name'              => $request->input('name'),
            'item_code'         => $request->input('item_code'),
            'hsn_code'          => $request->input('hsn_code'),
            'sku'               => $request->input('sku'),

            'godown_id'         => $request->input('godown_id'),

            'quantity'          => $request->input('quantity'),

            'price'             => $request->input('price'),

            'discount'          => $request->input('discount'),

            'price_1'           => $request->input('price_1'),

            'rate_2'            => $request->input('rate_2'),

            'rate_3'            => $request->input('rate_3'),

            'description'       => $request->input('description'),
        ]);


        /*
        |--------------------------------------------------------------------------
        | 2. Categories
        |--------------------------------------------------------------------------
        */

        $product->categories()->sync(
            $request->input('categories', [])
        );


        /*
        |--------------------------------------------------------------------------
        | 3. Vehicle Fitments
        |--------------------------------------------------------------------------
        */

        $product->fitments()->delete();

        if (!empty($fitmentRows)) {

            $product->fitments()->createMany(
                $fitmentRows
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 4. Tags
        |--------------------------------------------------------------------------
        */

        $product->tags()->sync(
            $request->input('tags', [])
        );


        /*
        |--------------------------------------------------------------------------
        | 5. Vehicle Companies
        |--------------------------------------------------------------------------
        */

        $product->select_companies()->sync(
            $request->input(
                'select_companies',
                []
            )
        );


        /*
        |--------------------------------------------------------------------------
        | 6. MAIN PHOTO
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Existing images are NOT looked for inside tmp/uploads.
        |
        | Only new Dropzone uploads are read from tmp/uploads.
        |
        */

        $existingPhotoIds =
            collect(
                $request->input(
                    'existing_photo_ids',
                    []
                )
            )
            ->map(fn($id) => (int) $id)
            ->filter()
            ->values()
            ->toArray();


        /*
        | Delete removed existing media
        */

        $product
            ->getMedia('photo')
            ->each(function ($media) use (
                $existingPhotoIds
            ) {

                if (
                    !in_array(
                        $media->id,
                        $existingPhotoIds
                    )
                ) {
                    $media->delete();
                }

            });


        /*
        | Add NEW uploaded files only
        */

        foreach (
            $request->input('photo', [])
            as $file
        ) {

            if (
                empty($file)
            ) {
                continue;
            }

            $tmpPath =
                storage_path(
                    'tmp/uploads/' .
                    basename($file)
                );


            /*
            | Prevent "file does not exist" error
            */

            if (
                !is_file($tmpPath)
            ) {
                continue;
            }


            $product
                ->addMedia($tmpPath)
                ->toMediaCollection('photo');
        }


        /*
        |--------------------------------------------------------------------------
        | 7. PRODUCT PHOTO 2
        |--------------------------------------------------------------------------
        */

        $existingPhoto2Ids =
            collect(
                $request->input(
                    'existing_product_photo_2_ids',
                    []
                )
            )
            ->map(fn($id) => (int) $id)
            ->filter()
            ->values()
            ->toArray();


        $product
            ->getMedia('product_photo_2')
            ->each(function ($media) use (
                $existingPhoto2Ids
            ) {

                if (
                    !in_array(
                        $media->id,
                        $existingPhoto2Ids
                    )
                ) {
                    $media->delete();
                }

            });


        foreach (
            $request->input(
                'product_photo_2',
                []
            ) as $file
        ) {

            if (
                empty($file)
            ) {
                continue;
            }


            $tmpPath =
                storage_path(
                    'tmp/uploads/' .
                    basename($file)
                );


            if (
                !is_file($tmpPath)
            ) {
                continue;
            }


            $product
                ->addMedia($tmpPath)
                ->toMediaCollection(
                    'product_photo_2'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | 8. PRODUCT PHOTO 3
        |--------------------------------------------------------------------------
        */

        $existingPhoto3Id =
            $request->input(
                'existing_product_photo_3_id'
            );


        $photo3 =
            $product->getMedia(
                'product_photo_3'
            );


        foreach ($photo3 as $media) {

            if (
                (int) $media->id !==
                (int) $existingPhoto3Id
            ) {

                $media->delete();

            }

        }


        $newPhoto3 =
            $request->input(
                'product_photo_3'
            );


        if (
            !empty($newPhoto3)
        ) {

            $tmpPath =
                storage_path(
                    'tmp/uploads/' .
                    basename($newPhoto3)
                );


            if (
                is_file($tmpPath)
            ) {

                $product
                    ->addMedia($tmpPath)
                    ->toMediaCollection(
                        'product_photo_3'
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 9. CKEditor Media
        |--------------------------------------------------------------------------
        */

        if (
            $media = $request->input(
                'ck-media',
                false
            )
        ) {

            Media::whereIn(
                'id',
                $media
            )->update([
                'model_id' => $product->id
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 10. Update Stock
        |--------------------------------------------------------------------------
        */

        $this->updateProductInOurStock(
            $product->fresh()
        );


        /*
        |--------------------------------------------------------------------------
        | 11. FOC / Scheme Slabs
        |--------------------------------------------------------------------------
        |
        | Old slabs delete
        | New slabs save
        |
        */

        $product
            ->focSlabs()
            ->delete();


        $focSlabs =
            $request->input(
                'foc_slabs',
                []
            );


        foreach (
            $focSlabs as $slab
        ) {

            $slabName =
                trim(
                    $slab['slab_name'] ?? ''
                );

            $buyQty =
                $slab['buy_qty'] ?? null;

            $freeQty =
                $slab['free_qty'] ?? null;


            /*
            | Ignore incomplete rows
            */

            if (
                $slabName === '' ||
                !$buyQty ||
                !$freeQty
            ) {
                continue;
            }


            $product
                ->focSlabs()
                ->create([
                    'slab_name' => $slabName,
                    'buy_qty'   => $buyQty,
                    'free_qty'  => $freeQty,
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | 12. Commit
        |--------------------------------------------------------------------------
        */

        DB::commit();


        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'success',
                'Product updated successfully.'
            );


    } catch (\Throwable $e) {

        DB::rollBack();


        return back()
            ->withInput()
            ->withErrors([
                'error' =>
                    'Product update failed: ' .
                    $e->getMessage()
            ]);
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
    abort_if(
        Gate::denies('product_delete'),
        Response::HTTP_FORBIDDEN,
        '403 Forbidden'
    );

    DB::beginTransaction();

    try {
        // Delete product related stock records
        OurStock::where('select_product_id', $product->id)->delete();

        // Delete product
        $product->delete();

        DB::commit();

        return back()->with('success', 'Product deleted successfully.');

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()->withErrors([
            'error' => 'Product delete failed: ' . $e->getMessage()
        ]);
    }
}

    public function massDestroy(MassDestroyProductRequest $request)
    {
        $fitmentRows = \App\Services\CatalogFitments::validateProduct($request);
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

    private function hierarchicalCategories()
    {
        return ProductCategory::where('is_subcategory', false)->orderBy('name')->pluck('name', 'id');
    }
}