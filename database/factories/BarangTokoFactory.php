<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangToko;
use App\Models\Toko;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BarangToko>
 */
class BarangTokoFactory extends Factory
{
    protected $model = BarangToko::class;

    public function definition(): array
    {
        $stokGudang = fake()->numberBetween(0, 500);
        $stokPacking = fake()->numberBetween(0, 200);

        return [
            'barang_id' => Barang::factory(),
            'toko_id' => Toko::factory(),
            'stok_gudang' => $stokGudang,
            'stok_packing' => $stokPacking,
            'total_stok' => $stokGudang + $stokPacking,
            'stock_threshold' => fake()->numberBetween(0, 50),
            'harga' => fake()->numberBetween(1000, 100000),
        ];
    }
}
