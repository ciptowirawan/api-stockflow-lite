<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => strtoupper($this->name),
        ]);
    }

    public function rules()
    {
        $categoryId = $this->route('category')?->id;
        
        return [
            'name' => 'required|string|max:255|unique:categories,name,' . $categoryId,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
        ];
    }
}
