<?php

namespace App\Models;

use Database\Factories\TokoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode_toko', 'nama_toko'])]
class Toko extends Model
{
    /** @use HasFactory<TokoFactory> */
    use HasFactory;

    public function barangToko(): HasMany
    {
        return $this->hasMany(BarangToko::class);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
