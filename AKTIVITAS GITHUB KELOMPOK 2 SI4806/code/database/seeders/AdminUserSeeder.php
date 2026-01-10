<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bookjournal.com'], // Cek apakah email ini sudah ada
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // Password default
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully.');
        $this->command->info('Email: admin@bookjournal.com');
        $this->command->info('Password: password123');
    }
}
