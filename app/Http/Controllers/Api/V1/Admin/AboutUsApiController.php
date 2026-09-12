<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAboutUsRequest;
use App\Http\Requests\UpdateAboutUsRequest;
use App\Http\Resources\Admin\AboutUsResource;
use App\Models\AboutUs;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AboutUsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('about_us_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AboutUsResource(AboutUs::with(['created_by'])->get());
    }

    public function store(StoreAboutUsRequest $request)
    {
        $aboutUs = AboutUs::create($request->all());

        if ($request->input('banner_image', false)) {
            $aboutUs->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner_image'))))->toMediaCollection('banner_image');
        }

        return (new AboutUsResource($aboutUs))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AboutUs $aboutUs)
    {
        abort_if(Gate::denies('about_us_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AboutUsResource($aboutUs->load(['created_by']));
    }

    public function update(UpdateAboutUsRequest $request, AboutUs $aboutUs)
    {
        $aboutUs->update($request->all());

        if ($request->input('banner_image', false)) {
            if (! $aboutUs->banner_image || $request->input('banner_image') !== $aboutUs->banner_image->file_name) {
                if ($aboutUs->banner_image) {
                    $aboutUs->banner_image->delete();
                }
                $aboutUs->addMedia(storage_path('tmp/uploads/' . basename($request->input('banner_image'))))->toMediaCollection('banner_image');
            }
        } elseif ($aboutUs->banner_image) {
            $aboutUs->banner_image->delete();
        }

        return (new AboutUsResource($aboutUs))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AboutUs $aboutUs)
    {
        abort_if(Gate::denies('about_us_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $aboutUs->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
