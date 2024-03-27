<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class ProductUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer', 
            'category_id' => 'required|exists:categories,id',
            'brand_id'=>'required|integer',
            'user_id'=>'required|integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.string' => 'Nama produk harus berupa teks.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga produk harus berupa angka.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok produk harus berupa bilangan bulat.',
            'brand_id.required' => 'ID Merek harus diisi',
            'brand_id.integer' => 'ID Merek harus berupa angka',
            'user_id.required' => 'ID Pengguna harus diisi',
            'user_id.integer' => 'ID Pengguna harus berupa angka',
            'photo.required' => 'Foto harus diisi',
            'photo.image' => 'Foto harus berupa gambar',
            'photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'photo.max' => 'Ukuran gambar tidak boleh lebih dari 5MB',
        ];
    }
    public function failedValidation(validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'validation erros',
            'data' => $validator->errors()
        ]));
    }
}

