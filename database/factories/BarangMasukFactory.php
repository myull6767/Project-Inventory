<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Supplier;
use App\Models\Toko;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BarangMasuk>
 */
class BarangMasukFactory extends Factory
{
    protected $model = BarangMasuk::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['supplier', 'packing']);

        return [
            'barang_id' => Barang::factory(),
            'supplier_id' => $type === 'supplier' ? Supplier::factory() : null,
            'quantity' => fake()->numberBetween(1, 100),
            'type' => $type,
            'toko_id' => Toko::factory(),
        ];
    }
}
