<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TokoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_toko' => ['required', Rule::unique('tokos')->ignore($this->route('toko'))],
            'nama_toko' => ['required', 'max:255'],
        ];
    }
}
