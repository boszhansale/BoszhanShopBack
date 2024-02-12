<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'article' => 'unique:products,article',
            'category_id' => '',
            'id_1c' => '',
            'measure' => '',
            'name' => '',
            'barcode' => '',
            'remainder' => '',
            'enabled' => '',
            'purchase' => '',
            'return' => '',
            'presale_id' => '',
            'discount' => '',
            'hit' => '',
            'new' => '',
            'action' => '',
            'discount_5' => '',
            'discount_10' => '',
            'discount_15' => '',
            'discount_20' => '',
            'rating' => '',
            'price' => '',

            'images' => 'array',
            'images.*' => 'image|max:1024'
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
