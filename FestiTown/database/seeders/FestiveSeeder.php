<?php

namespace Database\Seeders;

use App\Models\Festive;
use App\Models\Town;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FestiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $festives = [
            ['name' => 'Feria de Abril', 'date' => '2024-04-15', 'town_id' => 1], // Madrid
            ['name' => 'San Fermín', 'date' => '2024-07-06', 'town_id' => 2], // Barcelona
            ['name' => 'Fallas de Valencia', 'date' => '2024-03-19', 'town_id' => 3], // Valencia
            ['name' => 'Semana Santa', 'date' => '2024-03-24', 'town_id' => 4], // Sevilla
            ['name' => 'Fiesta del Pilar', 'date' => '2024-10-12', 'town_id' => 5], // Zaragoza
            ['name' => 'Feria de Málaga', 'date' => '2024-08-10', 'town_id' => 6], // Málaga
            ['name' => 'Bando de la Huerta', 'date' => '2024-04-10', 'town_id' => 7], // Murcia
            ['name' => 'Fiesta de San Juan', 'date' => '2024-06-24', 'town_id' => 8], // Palma
            ['name' => 'Carnaval de Las Palmas', 'date' => '2024-02-10', 'town_id' => 9], // Las Palmas
            ['name' => 'Aste Nagusia', 'date' => '2024-08-17', 'town_id' => 10], // Bilbao
            ['name' => 'Hogueras de San Juan', 'date' => '2024-06-24', 'town_id' => 11], // Alicante
            ['name' => 'Feria de Córdoba', 'date' => '2024-05-20', 'town_id' => 12], // Córdoba
            ['name' => 'Semana Santa de Valladolid', 'date' => '2024-03-24', 'town_id' => 13], // Valladolid
            ['name' => 'Fiesta de la Reconquista', 'date' => '2024-08-28', 'town_id' => 14], // Vigo
            ['name' => 'Fiesta de San Mateo', 'date' => '2024-09-21', 'town_id' => 15], // Gijón
        ];

        foreach ($festives as $festive) {
            Festive::create($festive);
        }

        // Create additional random festives
        Festive::factory(20)->create();
    }
}
