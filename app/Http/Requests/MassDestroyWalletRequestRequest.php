<?php

namespace App\Http\Requests;

use App\Models\WalletRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyWalletRequestRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('wallet_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:wallet_requests,id',
        ];
    }
}
