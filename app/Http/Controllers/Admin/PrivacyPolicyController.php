<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPrivacyPolicyRequest;
use App\Http\Requests\StorePrivacyPolicyRequest;
use App\Http\Requests\UpdatePrivacyPolicyRequest;
use App\Models\PrivacyPolicy;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PrivacyPolicyController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('privacy_policy_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $privacyPolicies = PrivacyPolicy::with(['created_by', 'media'])->get();

        return view('admin.privacyPolicies.index', compact('privacyPolicies'));
    }

    public function create()
    {
        abort_if(Gate::denies('privacy_policy_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.privacyPolicies.create');
    }

    public function store(StorePrivacyPolicyRequest $request)
    {
        $privacyPolicy = PrivacyPolicy::create($request->all());

        foreach ($request->input('banner_image', []) as $file) {
            $privacyPolicy->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('banner_image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $privacyPolicy->id]);
        }

        return redirect()->route('admin.privacy-policies.index');
    }

    public function edit(PrivacyPolicy $privacyPolicy)
    {
        abort_if(Gate::denies('privacy_policy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $privacyPolicy->load('created_by');

        return view('admin.privacyPolicies.edit', compact('privacyPolicy'));
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

        return redirect()->route('admin.privacy-policies.index');
    }

    public function show(PrivacyPolicy $privacyPolicy)
    {
        abort_if(Gate::denies('privacy_policy_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $privacyPolicy->load('created_by');

        return view('admin.privacyPolicies.show', compact('privacyPolicy'));
    }

    public function destroy(PrivacyPolicy $privacyPolicy)
    {
        abort_if(Gate::denies('privacy_policy_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $privacyPolicy->delete();

        return back();
    }

    public function massDestroy(MassDestroyPrivacyPolicyRequest $request)
    {
        $privacyPolicies = PrivacyPolicy::find(request('ids'));

        foreach ($privacyPolicies as $privacyPolicy) {
            $privacyPolicy->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('privacy_policy_create') && Gate::denies('privacy_policy_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new PrivacyPolicy();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
