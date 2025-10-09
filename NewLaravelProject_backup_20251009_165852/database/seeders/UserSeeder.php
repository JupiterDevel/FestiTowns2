<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Locality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pamplona = Locality::where('name', 'Pamplona')->first();

        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@festitowns.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'locality_id' => null,
            ],
            [
                'name' => 'Pamplona Town Hall',
                'email' => 'townhall@pamplona.com',
                'password' => Hash::make('password'),
                'role' => 'townhall',
                'locality_id' => $pamplona->id,
            ],
            [
                'name' => 'Visitor User',
                'email' => 'visitor@example.com',
                'password' => Hash::make('password'),
                'role' => 'visitor',
                'locality_id' => null,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
