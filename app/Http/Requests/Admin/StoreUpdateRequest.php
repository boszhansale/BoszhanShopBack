<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUpdateRequest extends FormRequest
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
            'counteragent_id' => 'exists:counteragents,id',
            'name' => 'required',
            'phone' => '',
            'address' => 'required',
            'lat' => '',
            'lng' => '',
            'discount' => '',
            'discount_position' => '',
        ];
    }

    public function messages()
    {
        return [
            'id_sell.unique' => 'id_sell уже занят'
        ];
    }

    public function failedValidation($validator)
    {
        throw new HttpResponseException(
            response()->json(['message' => $validator->errors()->first()], 400)
        );
    }
}
