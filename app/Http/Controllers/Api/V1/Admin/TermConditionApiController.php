<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreTermConditionRequest;
use App\Http\Requests\UpdateTermConditionRequest;
use App\Http\Resources\Admin\TermConditionResource;
use App\Models\TermCondition;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TermConditionApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('term_condition_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TermConditionResource(TermCondition::with(['created_by'])->get());
    }

    public function store(StoreTermConditionRequest $request)
    {
        $termCondition = TermCondition::create($request->all());

        if ($request->input('banner_image', false)) {
            $termCondition->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner_image'))))->toMediaCollection('banner_image');
        }

        return (new TermConditionResource($termCondition))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TermCondition $termCondition)
    {
        abort_if(Gate::denies('term_condition_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TermConditionResource($termCondition->load(['created_by']));
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

        return (new TermConditionResource($termCondition))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TermCondition $termCondition)
    {
        abort_if(Gate::denies('term_condition_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $termCondition->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
