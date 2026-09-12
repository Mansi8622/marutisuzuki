<?php

namespace App\Http\Requests;

use App\Models\StockTransfer;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreStockTransferRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('stock_transfer_create');
    }

    public function rules()
    {
        return [
            'select_products.*' => [
                'integer',
            ],
            'select_products' => [
                'required',
                'array',
            ],
            'select_user_id' => [
                'required',
                'integer',
            ],

        ];
    }
}
