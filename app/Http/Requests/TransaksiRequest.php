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
            'barang_id' => ['required', 'exists:barangs,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'kode_toko_inputed' => ['required', 'max:255'],
        ];
    }
}
