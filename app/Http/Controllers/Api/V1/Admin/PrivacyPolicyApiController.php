<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePrivacyPolicyRequest;
use App\Http\Requests\UpdatePrivacyPolicyRequest;
use App\Http\Resources\Admin\PrivacyPolicyResource;
use App\Models\PrivacyPolicy;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrivacyPolicyApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('privacy_policy_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PrivacyPolicyResource(PrivacyPolicy::with(['created_by'])->get());
    }

    public function store(StorePrivacyPolicyRequest $request)
    {
        $privacyPolicy = PrivacyPolicy::create($request->all());

        foreach ($request->input('banner_image', []) as $file) {
            $privacyPolicy->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('banner_image');
        }

        return (new PrivacyPolicyResource($privacyPolicy))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PrivacyPolicy $privacyPolicy)
    {
        abort_if(Gate::denies('privacy_policy_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PrivacyPolicyResource($privacyPolicy->load(['created_by']));
    }

    public function update(UpdatePrivacyPolicyRequest $request, PrivacyPolicy $privacyPolicy)
    {
        $privacyPolicy->update($request->all());

        if (count($privacyPolicy->banner_image) > 0) {
            foreach ($privacyPolicy->banner_image as $media) {
                if (! in_array($media->file_name, $request->input('banner_image', []))) {
                    $media->delete();
                }
            }
        }
        $media = $privacyPolicy->banner_image->pluck('file_name')->toArray();
        foreach ($request->input('banner_image', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $privacyPolicy->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('banner_image');
            }
        }

        return (new PrivacyPolicyResource($privacyPolicy))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PrivacyPolicy $privacyPolicy)
    {
        abort_if(Gate::denies('privacy_policy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $privacyPolicy->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
