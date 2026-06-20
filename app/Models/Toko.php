<?php

namespace App\Models;

use Database\Factories\TokoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode_toko', 'nama_toko'])]
class Toko extends Model
{
    /** @use HasFactory<TokoFactory> */
    use HasFactory;
}
