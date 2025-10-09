<?php

namespace Database\Seeders;

use App\Models\Town;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $towns = [
            ['name' => 'Madrid', 'province' => 'Madrid', 'photo' => 'https://picsum.photos/400/300?random=1'],
            ['name' => 'Barcelona', 'province' => 'Barcelona', 'photo' => 'https://picsum.photos/400/300?random=2'],
            ['name' => 'Valencia', 'province' => 'Valencia', 'photo' => 'https://picsum.photos/400/300?random=3'],
            ['name' => 'Sevilla', 'province' => 'Sevilla', 'photo' => 'https://picsum.photos/400/300?random=4'],
            ['name' => 'Zaragoza', 'province' => 'Zaragoza', 'photo' => 'https://picsum.photos/400/300?random=5'],
            ['name' => 'Málaga', 'province' => 'Málaga', 'photo' => 'https://picsum.photos/400/300?random=6'],
            ['name' => 'Murcia', 'province' => 'Murcia', 'photo' => 'https://picsum.photos/400/300?random=7'],
            ['name' => 'Palma', 'province' => 'Baleares', 'photo' => 'https://picsum.photos/400/300?random=8'],
            ['name' => 'Las Palmas', 'province' => 'Las Palmas', 'photo' => 'https://picsum.photos/400/300?random=9'],
            ['name' => 'Bilbao', 'province' => 'Vizcaya', 'photo' => 'https://picsum.photos/400/300?random=10'],
            ['name' => 'Alicante', 'province' => 'Alicante', 'photo' => 'https://picsum.photos/400/300?random=11'],
            ['name' => 'Córdoba', 'province' => 'Córdoba', 'photo' => 'https://picsum.photos/400/300?random=12'],
            ['name' => 'Valladolid', 'province' => 'Valladolid', 'photo' => 'https://picsum.photos/400/300?random=13'],
            ['name' => 'Vigo', 'province' => 'Pontevedra', 'photo' => 'https://picsum.photos/400/300?random=14'],
            ['name' => 'Gijón', 'province' => 'Asturias', 'photo' => 'https://picsum.photos/400/300?random=15'],
        ];

        foreach ($towns as $town) {
            Town::create($town);
        }

        // Create additional random towns
        Town::factory(10)->create();
    }
}
