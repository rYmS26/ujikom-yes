<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\user; // Assuming you have a User model in App\Models

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user::create([
            'username' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('userpassword'),
            'role' => 'petugas',
        ]);
        user::create([
            'username' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }
}
