<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // User::create([
        //     "name"=> "hai",
        //     "email"=> "halo@aa",
        //     "password"=> bcrypt("password123"),
        //     ]);
        $this->call(AkunSeeder::class);
        $this->call(JenisPlgSeeder::class);
        $this->call(TarifLogSeeder::class);
    }
}
