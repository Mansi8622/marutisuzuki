<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\ProductCategory;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class SubCategoryController extends Controller
{
    use MediaUploadingTrait;

    private function viewData(array $data = []): array
    {
        return array_merge(['isSubCategory' => true, 'routePrefix' => 'admin.sub-categories', 'permissionPrefix' => 'sub_category'], $data);
    }

    public function index() { abort_if(Gate::denies('sub_category_access'), 403); return view('admin.productCategories.index', $this->viewData(['productCategories' => ProductCategory::with('media')->where('is_subcategory', true)->get()])); }
    public function create() { abort_if(Gate::denies('sub_category_create'), 403); return view('admin.productCategories.create', $this->viewData()); }
    public function store(Request $request) {
        abort_if(Gate::denies('sub_category_create'), 403);
        $data = $request->validate(['name' => ['required', 'string'], 'description' => ['nullable', 'string'], 'photo' => ['nullable', 'string']]);
        $category = ProductCategory::create($data + ['has_subcategories' => false, 'is_subcategory' => true]);
        if ($request->input('photo')) $category->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        if ($media = $request->input('ck-media')) Media::whereIn('id', $media)->update(['model_id' => $category->id]);
        return redirect()->route('admin.sub-categories.index');
    }
    public function edit(ProductCategory $subCategory) { abort_if(Gate::denies('sub_category_edit'), 403); $this->ensureSubCategory($subCategory); return view('admin.productCategories.edit', $this->viewData(['productCategory' => $subCategory])); }
    public function update(Request $request, ProductCategory $subCategory) { abort_if(Gate::denies('sub_category_edit'), 403); $this->ensureSubCategory($subCategory); $data = $request->validate(['name' => ['required', 'string'], 'description' => ['nullable', 'string'], 'photo' => ['nullable', 'string']]); $subCategory->update($data + ['has_subcategories' => false, 'is_subcategory' => true]); if ($request->input('photo') && (! $subCategory->photo || $request->input('photo') !== $subCategory->photo->file_name)) { optional($subCategory->photo)->delete(); $subCategory->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo'); } elseif (! $request->input('photo') && $subCategory->photo) { $subCategory->photo->delete(); } return redirect()->route('admin.sub-categories.index'); }
    public function show(ProductCategory $subCategory) { abort_if(Gate::denies('sub_category_show'), 403); $this->ensureSubCategory($subCategory); return view('admin.productCategories.show', $this->viewData(['productCategory' => $subCategory])); }
    public function destroy(ProductCategory $subCategory) { abort_if(Gate::denies('sub_category_delete'), 403); $this->ensureSubCategory($subCategory); $subCategory->delete(); return back(); }
    public function massDestroy(Request $request) { abort_if(Gate::denies('sub_category_delete'), 403); $request->validate(['ids' => ['required', 'array']]); ProductCategory::whereIn('id', $request->ids)->where('is_subcategory', true)->delete(); return response(null, Response::HTTP_NO_CONTENT); }
    public function storeCKEditorImages(Request $request) { abort_if(Gate::denies('sub_category_create') && Gate::denies('sub_category_edit'), 403); $model = new ProductCategory; $model->id = $request->input('crud_id', 0); $model->exists = true; $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media'); return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED); }
    private function ensureSubCategory(ProductCategory $category): void { abort_if(! $category->is_subcategory, Response::HTTP_NOT_FOUND); }
}
