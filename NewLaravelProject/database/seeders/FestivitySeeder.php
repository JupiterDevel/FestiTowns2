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
        $sevilla = Locality::where('name', 'Sevilla')->first();

        $festivities = [
            [
                'locality_id' => $pamplona->id,
                'province' => 'Navarra',
                'name' => 'San Fermín',
                'start_date' => '2024-07-06',
                'end_date' => '2024-07-14',
                'description' => 'The world-famous Running of the Bulls festival. A week of non-stop partying, traditional costumes, and the thrilling encierro (bull run) through the streets of Pamplona.',
                'latitude' => 42.8181,
                'longitude' => -1.6443,
                'google_maps_url' => 'https://www.google.com/maps/place/Pamplona,+Navarra/@42.8181,-1.6443,15z',
                'photos' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800'
                ]
            ],
            [
                'locality_id' => $valencia->id,
                'province' => 'Valencia',
                'name' => 'Fallas',
                'start_date' => '2024-03-15',
                'end_date' => '2024-03-19',
                'description' => 'The spectacular Fallas festival features enormous papier-mâché sculptures, fireworks, and the burning of the fallas at the end of the celebration.',
                'latitude' => 39.4699,
                'longitude' => -0.3763,
                'google_maps_url' => 'https://www.google.com/maps/place/Valencia,+Spain/@39.4699,-0.3763,12z',
                'photos' => [
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800',
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800'
                ]
            ],
            [
                'locality_id' => $sevilla->id,
                'province' => 'Sevilla',
                'name' => 'Feria de Abril',
                'start_date' => '2024-04-14',
                'end_date' => '2024-04-20',
                'description' => 'Seville\'s most famous festival features flamenco dancing, traditional Andalusian costumes, and hundreds of colorful casetas (tents) in the fairground.',
                'latitude' => 37.3891,
                'longitude' => -5.9845,
                'google_maps_url' => 'https://www.google.com/maps/place/Sevilla,+Spain/@37.3891,-5.9845,13z',
                'photos' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800'
                ]
            ],
            [
                'locality_id' => $pamplona->id,
                'province' => 'Navarra',
                'name' => 'Semana Grande',
                'start_date' => '2024-08-12',
                'end_date' => '2024-08-18',
                'description' => 'A week-long celebration with concerts, fireworks, traditional Basque sports, and cultural events throughout Pamplona.',
                'latitude' => 42.8181,
                'longitude' => -1.6443,
                'google_maps_url' => 'https://www.google.com/maps/place/Pamplona,+Navarra/@42.8181,-1.6443,15z',
                'photos' => [
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800'
                ]
            ],
            [
                'locality_id' => $valencia->id,
                'province' => 'Valencia',
                'name' => 'La Tomatina',
                'start_date' => '2024-08-28',
                'end_date' => null,
                'description' => 'The world\'s biggest tomato fight! Thousands of people gather in Buñol (near Valencia) to throw tomatoes at each other in this unique festival.',
                'latitude' => 39.4194,
                'longitude' => -0.7906,
                'google_maps_url' => 'https://www.google.com/maps/place/Buñol,+Valencia/@39.4194,-0.7906,14z',
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
