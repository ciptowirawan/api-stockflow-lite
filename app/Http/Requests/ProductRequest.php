<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => strtoupper($this->name),
            'unit' => strtoupper($this->unit),
        ]);
    }

    public function rules()
    {
        $productId = $this->route('product') ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($productId)
            ],
            'unit' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.unique' => 'Nama produk sudah digunakan.',
            'unit.required' => 'Unit produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'category_id.required' => 'Kategori wajib diisi.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
        ];
    }
}
