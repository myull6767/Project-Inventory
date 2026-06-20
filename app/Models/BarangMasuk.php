<?php

namespace App\Models;

use Database\Factories\BarangMasukFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['barang_id', 'supplier_id', 'quantity', 'type'])]
class BarangMasuk extends Model
{
    /** @use HasFactory<BarangMasukFactory> */
    use HasFactory;

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
