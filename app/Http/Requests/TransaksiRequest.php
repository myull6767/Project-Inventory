<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pelanggan' => ['required', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.barang_id' => ['required', 'exists:barangs,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pelanggan.required' => 'Nama pelanggan harus diisi.',
            'items.required' => 'Minimal satu barang harus ditambahkan.',
            'items.min' => 'Minimal satu barang harus ditambahkan.',
            'items.*.barang_id.required' => 'Barang harus dipilih.',
            'items.*.quantity.required' => 'Jumlah barang harus diisi.',
            'items.*.quantity.min' => 'Jumlah barang minimal 1.',
        ];
    }
}
