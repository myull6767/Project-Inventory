<?php

namespace App\Models;

use Database\Factories\BarangFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode_barang', 'nama_barang', 'stok_gudang', 'stok_packing', 'total_stok', 'stock_threshold'])]
class Barang extends Model
{
    /** @use HasFactory<BarangFactory> */
    use HasFactory;

    public function barangMasuks(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function transaksiKeluars(): HasMany
    {
        return $this->hasMany(TransaksiKeluar::class);
    }
}
