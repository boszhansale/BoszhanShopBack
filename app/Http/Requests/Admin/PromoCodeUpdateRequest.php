<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PromoCodeUpdateRequest extends FormRequest
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
            'name'=>['string','required'],
            'discount' => ['required', 'numeric', 'min:0'],
            'phone' => ['required', 'string', 'min:10', 'max:10'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date'],
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
