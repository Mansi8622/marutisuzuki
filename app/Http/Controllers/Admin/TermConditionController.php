<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTermConditionRequest;
use App\Http\Requests\StoreTermConditionRequest;
use App\Http\Requests\UpdateTermConditionRequest;
use App\Models\TermCondition;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class TermConditionController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('term_condition_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $termConditions = TermCondition::with(['created_by', 'media'])->get();

        return view('admin.termConditions.index', compact('termConditions'));
    }

    public function create()
    {
        abort_if(Gate::denies('term_condition_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.termConditions.create');
    }

    public function store(StoreTermConditionRequest $request)
    {
        $termCondition = TermCondition::create($request->all());

        if ($request->input('banner_image', false)) {
            $termCondition->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner_image'))))->toMediaCollection('banner_image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $termCondition->id]);
        }

        return redirect()->route('admin.term-conditions.index');
    }

    public function edit(TermCondition $termCondition)
    {
        abort_if(Gate::denies('term_condition_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $termCondition->load('created_by');

        return view('admin.termConditions.edit', compact('termCondition'));
    }

    public function update(UpdateTermConditionRequest $request, TermCondition $termCondition)
    {
        $termCondition->update($request->all());

        if ($request->input('banner_image', false)) {
            if (! $termCondition->banner_image || $request->input('banner_image') !== $termCondition->banner_image->file_name) {
                if ($termCondition->banner_image) {
                    $termCondition->banner_image->delete();
                }
                $termCondition->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner_image'))))->toMediaCollection('banner_image');
            }
        } elseif ($termCondition->banner_image) {
            $termCondition->banner_image->delete();
        }

        return redirect()->route('admin.term-conditions.index');
    }

    public function show(TermCondition $termCondition)
    {
        abort_if(Gate::denies('term_condition_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $termCondition->load('created_by');

        return view('admin.termConditions.show', compact('termCondition'));
    }

    public function destroy(TermCondition $termCondition)
    {
        abort_if(Gate::denies('term_condition_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $termCondition->delete();

        return back();
    }

    public function massDestroy(MassDestroyTermConditionRequest $request)
    {
        $termConditions = TermCondition::find(request('ids'));

        foreach ($termConditions as $termCondition) {
            $termCondition->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('term_condition_create') && Gate::denies('term_condition_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new TermCondition();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
