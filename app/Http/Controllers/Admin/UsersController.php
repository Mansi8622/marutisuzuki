<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::with(['roles', 'media'])->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));
        foreach ($request->input('kyc_documents_front', []) as $file) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('kyc_documents_front');
        }

        if ($request->input('kyc_documents_back', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('kyc_documents_back'))))->toMediaCollection('kyc_documents_back');
        }

        foreach ($request->input('business_registration_certificate', []) as $file) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('business_registration_certificate');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        if (count($user->kyc_documents_front) > 0) {
            foreach ($user->kyc_documents_front as $media) {
                if (! in_array($media->file_name, $request->input('kyc_documents_front', []))) {
                    $media->delete();
                }
            }
        }
        $media = $user->kyc_documents_front->pluck('file_name')->toArray();
        foreach ($request->input('kyc_documents_front', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $user->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('kyc_documents_front');
            }
        }

        if ($request->input('kyc_documents_back', false)) {
            if (! $user->kyc_documents_back || $request->input('kyc_documents_back') !== $user->kyc_documents_back->file_name) {
                if ($user->kyc_documents_back) {
                    $user->kyc_documents_back->delete();
                }
                $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('kyc_documents_back'))))->toMediaCollection('kyc_documents_back');
            }
        } elseif ($user->kyc_documents_back) {
            $user->kyc_documents_back->delete();
        }

        if (count($user->business_registration_certificate) > 0) {
            foreach ($user->business_registration_certificate as $media) {
                if (! in_array($media->file_name, $request->input('business_registration_certificate', []))) {
                    $media->delete();
                }
            }
        }
        $media = $user->business_registration_certificate->pluck('file_name')->toArray();
        foreach ($request->input('business_registration_certificate', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $user->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('business_registration_certificate');
            }
        }

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'selectUserStockTransfers', 'selectUserCheckOrders', 'customerDisputes', 'vendorWalletRequests', 'userUserAlerts');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('user_create') && Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new User();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
