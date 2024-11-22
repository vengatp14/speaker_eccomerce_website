<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'total_price' => 'required|numeric|min:0',
            'order_date' => 'sometimes|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'customer_id.required' => 'A customer ID is required.',
            'customer_id.exists' => 'The selected customer does not exist.',
            'total_price.required' => 'A total price is required.',
            'total_price.numeric' => 'The total price must be a number.',
            'total_price.min' => 'The total price must be at least 0.',
            'order_date.date' => 'The order date must be a valid date.',
        ];
    }
}
