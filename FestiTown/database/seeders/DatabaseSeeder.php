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
        // Create users
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@festitowns.com',
        ]);

        User::factory(5)->create();

        // Create towns with specific data
        $this->call([
            TownSeeder::class,
            FestiveSeeder::class,
            AdverticementsSeeder::class,
        ]);
    }
}
