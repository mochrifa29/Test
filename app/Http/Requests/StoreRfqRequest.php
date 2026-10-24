<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRfqRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sku' => ['required', 'string', 'distinct'],
            'items.*.product_name' => ['required', 'string'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'company.required' => 'Nama perusahaan (company) wajib diisi.',
            'items.required' => 'Daftar produk (items) minimal harus ada satu.',
            'items.min' => 'Daftar produk (items) minimal harus ada satu.',
            'items.*.sku.required' => 'SKU produk wajib diisi.',
            'items.*.sku.distinct' => 'SKU tidak boleh duplikat dalam satu RFQ.',
            'items.*.product_name.required' => 'Nama produk wajib diisi.',
            'items.*.qty.required' => 'Kuantitas (qty) wajib diisi.',
            'items.*.qty.integer' => 'Kuantitas (qty) harus berupa bilangan bulat.',
            'items.*.qty.min' => 'Kuantitas (qty) harus lebih besar dari 0.',
            'items.*.unit_price.required' => 'Harga satuan (unit_price) wajib diisi.',
            'items.*.unit_price.numeric' => 'Harga satuan (unit_price) harus berupa angka.',
            'items.*.unit_price.min' => 'Harga satuan (unit_price) tidak boleh kurang dari 0.',
        ];
    }
}
