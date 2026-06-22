<?php

namespace App\Models;

use Database\Factories\TransaksiKeluarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['transaksi_id', 'barang_id', 'quantity', 'stok_awal_snapshot', 'harga_snapshot'])]
class TransaksiKeluar extends Model
{
    /** @use HasFactory<TransaksiKeluarFactory> */
    use HasFactory;

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
