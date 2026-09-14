<?php

namespace App\Http\Requests;

use App\Models\WalletRequest;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreWalletRequestRequest extends FormRequest
{
    public function authorize()
    {
        // Any authenticated retailer may apply; approval remains admin-controlled.
        return auth()->check();
    }

    public function rules()
    {
        return ['welcome_amount' => ['required', 'numeric', 'min:1']];
    }
}
