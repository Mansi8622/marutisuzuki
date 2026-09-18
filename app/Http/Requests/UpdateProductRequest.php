<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_edit');
    }

    public function rules()
    {
        $productId = $this->route('product')->id;

        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => [
                'string',
                'required',
            ],

            'item_code' => [
                'string',
                'required',
                'unique:products,item_code,' . $productId,
            ],

            'hsn_code' => [
                'string',
                'required',
                'unique:products,hsn_code,' . $productId,
            ],

            'sku' => [
                'string',
                'required',
                'unique:products,sku,' . $productId,
            ],


            /*
            |--------------------------------------------------------------------------
            | Categories
            |--------------------------------------------------------------------------
            */

            'categories' => [
                'nullable',
                'array',
            ],

            'categories.*' => [
                'integer',
            ],


            /*
            |--------------------------------------------------------------------------
            | Tags
            |--------------------------------------------------------------------------
            */

            'tags' => [
                'nullable',
                'array',
            ],

            'tags.*' => [
                'integer',
            ],


            /*
            |--------------------------------------------------------------------------
            | Vehicle Companies
            |--------------------------------------------------------------------------
            */

            'select_companies' => [
                'required',
                'array',
                'min:1',
            ],

            'select_companies.*' => [
                'integer',
            ],


            /*
            |--------------------------------------------------------------------------
            | Godown
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | Edit page now uses godown_id instead of old "godown".
            |
            */

            'godown_id' => [
                'required',
                'integer',
                'exists:godowns,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Stock & Pricing
            |--------------------------------------------------------------------------
            */

            'quantity' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],

            'price_1' => [
                'required',
                'numeric',
                'min:0',
            ],

            'rate_2' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rate_3' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            'description' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Existing Media
            |--------------------------------------------------------------------------
            |
            | These are IDs of media which already exist.
            |
            */

            'existing_photo_ids' => [
                'nullable',
                'array',
            ],

            'existing_photo_ids.*' => [
                'integer',
            ],

            'existing_product_photo_2_ids' => [
                'nullable',
                'array',
            ],

            'existing_product_photo_2_ids.*' => [
                'integer',
            ],

            'existing_product_photo_3_id' => [
                'nullable',
                'integer',
            ],


            /*
            |--------------------------------------------------------------------------
            | Newly Uploaded Temporary Media
            |--------------------------------------------------------------------------
            */

            'photo' => [
                'nullable',
                'array',
            ],

            'photo.*' => [
                'nullable',
                'string',
            ],

            'product_photo_2' => [
                'nullable',
                'array',
            ],

            'product_photo_2.*' => [
                'nullable',
                'string',
            ],

            'product_photo_3' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | FOC / Scheme Slabs
            |--------------------------------------------------------------------------
            */

            'foc_slabs' => [
                'nullable',
                'array',
            ],

            'foc_slabs.*.slab_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'foc_slabs.*.buy_qty' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'foc_slabs.*.free_qty' => [
                'nullable',
                'integer',
                'min:1',
            ],

        ];
    }
}