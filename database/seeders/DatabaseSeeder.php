<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder untuk Admin
        $admin = User::factory()->create([
            'nama' => 'Maulanaganteng123',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'status' => 'aktif',

        ]);

        Role::create([
            'id_user' => $admin->id,
            'role' => 'admin',
        ]);

        // Seeder untuk Pengelola
        $pengelola = User::factory()->create([
            'nama' => 'Maulanaganteng123',
            'email' => 'pengguna3@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'pengelola',
            'status' => 'aktif',

        ]);

        Role::create([
            'id_user' => $pengelola->id,
            'role' => 'pengelola',
        ]);

        // Seeder untuk User Biasa
        $user = User::factory()->create([
            'nama' => 'Maulanaganteng123',
            'email' => 'pengguna2@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'user',
            'status' => 'aktif',

        ]);

        Role::create([
            'id_user' => $user->id,
            'role' => 'user',
        ]);
    }
}
