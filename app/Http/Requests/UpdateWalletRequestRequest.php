<?php

namespace App\Http\Requests;

use App\Models\WalletRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateWalletRequestRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('wallet_request_edit');
    }

    public function rules()
    {
        return [];
    }
}
