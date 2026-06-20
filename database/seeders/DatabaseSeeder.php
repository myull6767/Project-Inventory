<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        Role::create(['name' => 'staff']);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@ljn.com',
            'role_id' => $admin->id,
        ]);
    }
}
