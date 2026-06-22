<?php

namespace Database\Factories;

use App\Models\Toko;
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
            'toko_id' => Toko::factory(),
            'user_id' => User::factory(),
        ];
    }
}
