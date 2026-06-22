<?php

namespace App\Models;

use Database\Factories\BarangFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode_barang', 'nama_barang'])]
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

    public function tokos(): BelongsToMany
    {
        return $this->belongsToMany(Toko::class, 'barang_toko')
            ->withPivot(['stok_gudang', 'stok_packing', 'total_stok', 'stock_threshold'])
            ->withTimestamps()
            ->using(BarangToko::class);
    }

    public function stokDiToko(int $tokoId): ?BarangToko
    {
        return BarangToko::where('barang_id', $this->id)
            ->where('toko_id', $tokoId)
            ->first();
    }
}
