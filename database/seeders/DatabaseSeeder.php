<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat akun ADMIN
        User::create([
            'name' => 'Administrator IdeaHub',
            'email' => 'admin@ideahub.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin', // ← Kolom ini dari migration ideahub_tables
            'phone_number' => '081234567890',
        ]);

        // 2. Buat akun USER/KAMPUS untuk testing (opsional)
        User::create([
            'name' => 'PIC Telkom University',
            'email' => 'telkom@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'university_name' => 'Telkom University',
            'university_type' => 'Swasta',
            'university_category' => 'Universitas',
            'has_disability_study_program' => true,
            'phone_number' => '082345678901',
        ]);

        // 3. Jalankan SurveySeeder (untuk isi pertanyaan survei)
        $this->call([
            SurveySeeder::class,
        ]);
    }
}
