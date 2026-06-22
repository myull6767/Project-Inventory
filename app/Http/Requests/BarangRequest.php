<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_barang' => ['required', Rule::unique('barangs')->ignore($this->route('barang'))],
            'nama_barang' => ['required', 'max:255'],
            'stok_gudang' => ['nullable', 'integer', 'min:0'],
            'stok_packing' => ['nullable', 'integer', 'min:0'],
            'stock_threshold' => ['nullable', 'integer', 'min:0'],
            'harga' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
