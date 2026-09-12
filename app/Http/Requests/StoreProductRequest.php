<?php

namespace App\Http\Requests;

use App\Models\Product;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'categories.*' => [
                'integer',
            ],
            'categories' => [
                'array',
            ],
            'tags.*' => [
                'integer',
            ],
            'tags' => [
                'array',
            ],
            'select_companies.*' => [
                'integer',
            ],
            'select_companies' => [
                'required',
                'array',
            ],
            'item_code' => [
                'string',
                'required',
                'unique:products',
            ],
            'hsn_code' => [
                'string',
                'required',
                'unique:products',
            ],
            'godown_id' => [
                
                'required',
            ],
            'price' => [
                'required',
            ],
            'discount' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'price_1' => [
                'required',
            ],
            'photo' => [
                'array',
                'required',
            ],
            'photo.*' => [
                'required',
            ],
            'product_photo_2' => [
                'array',
            ],
            'quantity' => [
                'string',
                'required',
                
            ],
        ];
    }
}
