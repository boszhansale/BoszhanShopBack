<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreRequest extends FormRequest
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
            'name' => 'required',
            'phone' => '',
            'login' => '',
            'password' => '',
            'id_1c' => '',
            'webkassa_login' => '',
            'webkassa_password' => '',
            'webkassa_cash_box_id' => '',
            'store_id' => 'required|exists:stores,id',
            'storage_id' => 'required|exists:storages,id',
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
