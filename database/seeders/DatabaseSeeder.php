<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'staff']);

        Toko::firstOrCreate(['kode_toko' => 'ANNA'], ['nama_toko' => 'ANNA WIFI']);
        Toko::firstOrCreate(['kode_toko' => 'PEL'], ['nama_toko' => 'PELAUKAN']);
        Toko::firstOrCreate(['kode_toko' => 'VIL'], ['nama_toko' => 'VILLA KENCANA']);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@ljn.com',
            'role_id' => $admin->id,
        ]);

        $this->call(BarangSeeder::class);
    }
}
