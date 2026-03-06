<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Tambahkan ini

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat 1 user admin
        User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'password' => Hash::make('admin123'), // Pastikan password di-hash
            'role' => 'admin',
        ]);

        // Tambahan: buat user biasa juga
        User::factory()->create([
            'name' => 'User Biasa',
            'username' => 'user1',
            'password' => Hash::make('user123'),
            'role' => 'user',
        ]);
    }
}
