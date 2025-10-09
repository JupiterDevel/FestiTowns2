<?php

namespace Database\Seeders;

use App\Models\Festivity;
use App\Models\Locality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FestivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pamplona = Locality::where('name', 'Pamplona')->first();
        $valencia = Locality::where('name', 'Valencia')->first();
        $seville = Locality::where('name', 'Seville')->first();

        $festivities = [
            [
                'locality_id' => $pamplona->id,
                'name' => 'San Fermín',
                'start_date' => '2024-07-06',
                'end_date' => '2024-07-14',
                'description' => 'The world-famous Running of the Bulls festival. A week of non-stop partying, traditional costumes, and the thrilling encierro (bull run) through the streets of Pamplona.',
                'photos' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800'
                ]
            ],
            [
                'locality_id' => $valencia->id,
                'name' => 'Fallas',
                'start_date' => '2024-03-15',
                'end_date' => '2024-03-19',
                'description' => 'The spectacular Fallas festival features enormous papier-mâché sculptures, fireworks, and the burning of the fallas at the end of the celebration.',
                'photos' => [
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800',
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800'
                ]
            ],
            [
                'locality_id' => $seville->id,
                'name' => 'Feria de Abril',
                'start_date' => '2024-04-14',
                'end_date' => '2024-04-20',
                'description' => 'Seville\'s most famous festival features flamenco dancing, traditional Andalusian costumes, and hundreds of colorful casetas (tents) in the fairground.',
                'photos' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800'
                ]
            ],
            [
                'locality_id' => $pamplona->id,
                'name' => 'Semana Grande',
                'start_date' => '2024-08-12',
                'end_date' => '2024-08-18',
                'description' => 'A week-long celebration with concerts, fireworks, traditional Basque sports, and cultural events throughout Pamplona.',
                'photos' => [
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800'
                ]
            ],
            [
                'locality_id' => $valencia->id,
                'name' => 'La Tomatina',
                'start_date' => '2024-08-28',
                'end_date' => null,
                'description' => 'The world\'s biggest tomato fight! Thousands of people gather in Buñol (near Valencia) to throw tomatoes at each other in this unique festival.',
                'photos' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800'
                ]
            ]
        ];

        foreach ($festivities as $festivity) {
            Festivity::create($festivity);
        }
    }
}
