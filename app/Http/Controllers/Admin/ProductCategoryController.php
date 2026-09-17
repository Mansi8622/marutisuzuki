<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductCategoryRequest;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('product_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productCategories = ProductCategory::where('is_subcategory', false)->with(['media', 'subcategories.vehicles', 'parent'])->orderBy('name')->get();

        return view('admin.productCategories.index', compact('productCategories'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parents = ProductCategory::orderBy('name')->get();

        return view('admin.productCategories.create', compact('parents'));
    }

    public function store(StoreProductCategoryRequest $request)
    {
        $data = $request->validated();
        $data['is_subcategory'] = false;
        $data['parent_id'] = null;
        $data['has_subcategories'] = $request->boolean('has_subcategories');
        $productCategory = ProductCategory::create($data);
        $productCategory->subcategories()->sync($data['has_subcategories'] ? $request->input('subcategories', []) : []);

        if ($request->input('photo', false)) {
            $productCategory->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $productCategory->id]);
        }

        return redirect()->route('admin.product-categories.index');
    }

    public function edit(ProductCategory $productCategory)
    {
        abort_if(Gate::denies('product_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $parents = ProductCategory::whereKeyNot($productCategory->id)->orderBy('name')->get();
        return view('admin.productCategories.edit', compact('productCategory', 'parents'));
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $data = $request->validated();
        abort_if(isset($data['parent_id']) && (int) $data['parent_id'] === $productCategory->id, Response::HTTP_UNPROCESSABLE_ENTITY);
        $data['is_subcategory'] = false;
        $data['parent_id'] = null;
        $data['has_subcategories'] = $request->boolean('has_subcategories');
        $productCategory->update($data);
        $productCategory->subcategories()->sync($data['has_subcategories'] ? $request->input('subcategories', []) : []);

        if ($request->input('photo', false)) {
            if (! $productCategory->photo || $request->input('photo') !== $productCategory->photo->file_name) {
                if ($productCategory->photo) {
                    $productCategory->photo->delete();
                }
                $productCategory->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($productCategory->photo) {
            $productCategory->photo->delete();
        }

        return redirect()->route('admin.product-categories.index');
    }

    public function show(ProductCategory $productCategory)
    {
        abort_if(Gate::denies('product_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productCategory->load('children');

        return view('admin.productCategories.show', compact('productCategory'));
    }

    public function destroy(ProductCategory $productCategory)
    {
        abort_if(Gate::denies('product_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $productCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductCategoryRequest $request)
    {
        $productCategories = ProductCategory::find(request('ids'));

        foreach ($productCategories as $productCategory) {
            $productCategory->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('product_category_create') && Gate::denies('product_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ProductCategory();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
