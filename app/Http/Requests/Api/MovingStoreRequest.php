<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MovingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'payment_type' => ['numeric'],
            'operation' => ['required','in:1,2'],
            'storage_id' => ['exists:storages,id'],
            'order_id' => ['exists:orders,id'],

            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.comment' => 'string',
            'products.*.count' => 'required',
            'products.*.price' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'login.exists' => 'неверный логин',
        ];
    }

    public function failedValidation($validator)
    {
        throw new HttpResponseException(
            response()->json(['message' => $validator->errors()->first()], 400)
        );
    }
}
