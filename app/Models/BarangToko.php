<?php

namespace App\Models;

use Database\Factories\BarangTokoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[Fillable(['barang_id', 'toko_id', 'stok_gudang', 'stok_packing', 'total_stok', 'stock_threshold', 'harga'])]
class BarangToko extends Pivot
{
    /** @use HasFactory<BarangTokoFactory> */
    use HasFactory;

    public $incrementing = true;

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function toko(): BelongsTo
    {
        return $this->belongsTo(Toko::class);
    }
}
