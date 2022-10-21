<?php

namespace App\Http\Requests\Coupon;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        //    -> Validations for storing API via POSTMAN  <-
        return [
            'type_id' => 'required|numeric|exists:types,id',
            'limit' => 'nullable|numeric|min:1',
            'expires_at' => 'nullable|date|after:now',
            'value' => 'required_unless:type_id, 3|nullable|numeric|min:1|max:99',
            'email' => 'nullable|email'
        ];
    }
}
