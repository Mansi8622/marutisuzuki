<?php

namespace App\Http\Requests;

use App\Models\ProductCategory;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateProductCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('product_category_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'description' => ['nullable', 'string'],
            'has_subcategories' => ['required', 'boolean'],
            'subcategories' => ['required_if:has_subcategories,1', 'array'],
            'subcategories.*' => ['integer', \Illuminate\Validation\Rule::exists('product_categories', 'id')->where('is_subcategory', true)->whereNull('deleted_at')],
        ];
    }
}
