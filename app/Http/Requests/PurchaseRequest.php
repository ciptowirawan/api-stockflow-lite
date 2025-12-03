<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'purchase_date' => 'required|date|before_or_equal:today',
            'supplier_id'   => 'required|exists:suppliers,id',
            'grand_total'   => 'required|numeric|min:0',

            'products'              => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty'        => 'required|integer|min:1',
            'products.*.price'      => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'purchase_date.before_or_equal' => 'Tanggal purchase tidak boleh melebihi tanggal hari ini.',
        ];
    }
}
