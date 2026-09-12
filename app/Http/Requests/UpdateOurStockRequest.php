<?php

namespace App\Http\Requests;

use App\Models\OurStock;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOurStockRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('our_stock_edit');
    }

    public function rules()
    {
        return [
            'select_product_id' => [
                'required',
                'integer',
            ],
            'quantity_available' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'sku' => [
                'string',
                'nullable',
            ],
        ];
    }
}
