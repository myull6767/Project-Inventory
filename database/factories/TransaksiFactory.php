<?php

namespace Database\Factories;

use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaksi>
 */
class TransaksiFactory extends Factory
{
    protected $model = Transaksi::class;

    public function definition(): array
    {
        return [
            'kode_toko_inputed' => 'TKO-'.str_pad(fake()->unique()->numberBetween(1, 999), 2, '0', STR_PAD_LEFT),
            'user_id' => User::factory(),
        ];
    }
}
