<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Administrator Sintara',
            'email' => 'admin@sintara.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Dosen
        User::factory()->create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'dosen@sintara.com',
            'password' => bcrypt('password'),
            'role' => 'dosen',
        ]);

        // Mahasiswa
        User::factory()->create([
            'name' => 'Adi Wijaya',
            'email' => 'mahasiswa@sintara.com',
            'password' => bcrypt('password'),
            'role' => 'mahasiswa',
        ]);
    }
}
