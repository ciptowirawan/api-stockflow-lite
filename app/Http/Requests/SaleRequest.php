<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_date' => 'required|date|before_or_equal:today',
            'customer_id' => 'required|exists:customers,id',
            'grand_total' => 'required|numeric|min:0',
            
            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $grandTotal = $this->grand_total ?? 0;

                    if ($value < $grandTotal) {
                        $fail("Paid amount must be at least the grand total of {$grandTotal}.");
                    }
                },
            ],

            'products' => 'required|array|min:1',
            'products.*.product_id'  => 'required|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'order_date.before_or_equal' => 'Tanggal order tidak boleh melebihi tanggal hari ini.',
        ];
    }
}
