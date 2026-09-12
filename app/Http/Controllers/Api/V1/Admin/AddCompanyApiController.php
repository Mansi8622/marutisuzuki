<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAddCompanyRequest;
use App\Http\Requests\UpdateAddCompanyRequest;
use App\Http\Resources\Admin\AddCompanyResource;
use App\Models\AddCompany;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCompanyApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('add_company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddCompanyResource(AddCompany::with(['created_by'])->get());
    }

    public function store(StoreAddCompanyRequest $request)
    {
        $addCompany = AddCompany::create($request->all());

        foreach ($request->input('company_logo', []) as $file) {
            $addCompany->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('company_logo');
        }

        return (new AddCompanyResource($addCompany))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(AddCompany $addCompany)
    {
        abort_if(Gate::denies('add_company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AddCompanyResource($addCompany->load(['created_by']));
    }

    public function update(UpdateAddCompanyRequest $request, AddCompany $addCompany)
    {
        $addCompany->update($request->all());

        if (count($addCompany->company_logo) > 0) {
            foreach ($addCompany->company_logo as $media) {
                if (! in_array($media->file_name, $request->input('company_logo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $addCompany->company_logo->pluck('file_name')->toArray();
        foreach ($request->input('company_logo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $addCompany->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('company_logo');
            }
        }

        return (new AddCompanyResource($addCompany))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(AddCompany $addCompany)
    {
        abort_if(Gate::denies('add_company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addCompany->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
