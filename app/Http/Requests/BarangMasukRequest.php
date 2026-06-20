<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = $this->input('type', 'supplier');

        return [
            'barang_id' => ['required', 'exists:barangs,id'],
            'supplier_id' => $type === 'supplier' ? ['required', 'exists:suppliers,id'] : ['nullable'],
            'quantity' => ['required', 'integer', 'min:1'],
            'type' => ['nullable', 'in:supplier,packing'],
        ];
    }
}
