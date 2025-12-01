<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockDetailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|not_in:0',
            'type' => 'required|in:P_RET,S_RET,ADJ',
            'created_by' => 'nullable|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Produk wajib diisi.',
            'product_id.exists' => 'Produk tidak ditemukan.',
            'quantity.required' => 'Jumlah wajib diisi.',
            'quantity.not_in' => 'Jumlah tidak boleh 0.',
            'type.required' => 'Tipe stok wajib diisi.',
            'type.in' => 'Tipe stok harus salah satu dari P_RET, S_RET, ADJ.',
        ];
    }
}
