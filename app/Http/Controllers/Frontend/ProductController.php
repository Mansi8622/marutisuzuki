<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\AddCompany;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::with(['categories', 'tags', 'select_companies', 'created_by', 'media'])->get();

        return view('frontend.products.index', compact('products'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::pluck('name', 'id');

        $tags = ProductTag::pluck('name', 'id');

        $select_companies = AddCompany::pluck('company_name', 'id');

        return view('frontend.products.create', compact('categories', 'select_companies', 'tags'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->categories()->sync($request->input('categories', []));
        $product->tags()->sync($request->input('tags', []));
        $product->select_companies()->sync($request->input('select_companies', []));
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

        return redirect()->route('frontend.products.index');
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = ProductCategory::pluck('name', 'id');

        $tags = ProductTag::pluck('name', 'id');

        $select_companies = AddCompany::pluck('company_name', 'id');

        $product->load('categories', 'tags', 'select_companies', 'created_by');

        return view('frontend.products.edit', compact('categories', 'product', 'select_companies', 'tags'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());
        $product->categories()->sync($request->input('categories', []));
        $product->tags()->sync($request->input('tags', []));
        $product->select_companies()->sync($request->input('select_companies', []));
        if (count($product->photo) > 0) {
            foreach ($product->photo as $media) {
                if (! in_array($media->file_name, $request->input('photo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $product->photo->pluck('file_name')->toArray();
        foreach ($request->input('photo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo');
            }
        }

        if (count($product->product_photo_2) > 0) {
            foreach ($product->product_photo_2 as $media) {
                if (! in_array($media->file_name, $request->input('product_photo_2', []))) {
                    $media->delete();
                }
            }
        }
        $media = $product->product_photo_2->pluck('file_name')->toArray();
        foreach ($request->input('product_photo_2', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $product->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('product_photo_2');
            }
        }

        if ($request->input('product_photo_3', false)) {
            if (! $product->product_photo_3 || $request->input('product_photo_3') !== $product->product_photo_3->file_name) {
                if ($product->product_photo_3) {
                    $product->product_photo_3->delete();
                }
                $product->addMedia(storage_path('tmp/uploads/' . basename($request->input('product_photo_3'))))->toMediaCollection('product_photo_3');
            }
        } elseif ($product->product_photo_3) {
            $product->product_photo_3->delete();
        }

        return redirect()->route('frontend.products.index');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load('categories', 'tags', 'select_companies', 'created_by');

        return view('frontend.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        $products = Product::find(request('ids'));

        foreach ($products as $product) {
            $product->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_create') && Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Product();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
