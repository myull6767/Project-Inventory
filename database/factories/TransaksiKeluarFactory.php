<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\TransaksiKeluar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransaksiKeluar>
 */
class TransaksiKeluarFactory extends Factory
{
    protected $model = TransaksiKeluar::class;

    public function definition(): array
    {
        return [
            'transaksi_id' => Transaksi::factory(),
            'barang_id' => Barang::factory(),
            'quantity' => fake()->numberBetween(1, 100),
            'stok_awal_snapshot' => fn (array $attrs) => $attrs['quantity'] + fake()->numberBetween(10, 500),
        ];
    }
}
