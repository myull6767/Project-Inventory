<?php

namespace App\Models;

use Database\Factories\TransaksiKeluarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['barang_id', 'user_id', 'kode_toko_inputed', 'quantity', 'stok_awal_snapshot'])]
class TransaksiKeluar extends Model
{
    /** @use HasFactory<TransaksiKeluarFactory> */
    use HasFactory;

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
