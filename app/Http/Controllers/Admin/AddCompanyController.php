<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAddCompanyRequest;
use App\Http\Requests\StoreAddCompanyRequest;
use App\Http\Requests\UpdateAddCompanyRequest;
use App\Models\AddCompany;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AddCompanyController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('add_company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addCompanies = AddCompany::with(['created_by', 'media'])->get();

        return view('admin.addCompanies.index', compact('addCompanies'));
    }

    public function create()
    {
        abort_if(Gate::denies('add_company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.addCompanies.create');
    }

    public function store(StoreAddCompanyRequest $request)
    {
        $addCompany = AddCompany::create($request->all());

        foreach ($request->input('company_logo', []) as $file) {
            $addCompany->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('company_logo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $addCompany->id]);
        }

        return redirect()->route('admin.add-companies.index');
    }

    public function edit(AddCompany $addCompany)
    {
        abort_if(Gate::denies('add_company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addCompany->load('created_by');

        return view('admin.addCompanies.edit', compact('addCompany'));
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

        return redirect()->route('admin.add-companies.index');
    }

    public function show(AddCompany $addCompany)
    {
        abort_if(Gate::denies('add_company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addCompany->load('created_by', 'companyRefunds', 'selectCompanyProducts');

        return view('admin.addCompanies.show', compact('addCompany'));
    }

    public function destroy(AddCompany $addCompany)
    {
        abort_if(Gate::denies('add_company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $addCompany->delete();

        return back();
    }

    public function massDestroy(MassDestroyAddCompanyRequest $request)
    {
        $addCompanies = AddCompany::find(request('ids'));

        foreach ($addCompanies as $addCompany) {
            $addCompany->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('add_company_create') && Gate::denies('add_company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new AddCompany();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
