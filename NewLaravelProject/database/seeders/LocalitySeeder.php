<?php

namespace Database\Seeders;

use App\Models\Locality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $localities = [
            [
                'name' => 'Pamplona',
                'address' => 'Navarra, Spain',
                'province' => 'Navarra',
                'description' => 'Pamplona is the capital city of Navarra, famous for the Running of the Bulls during the San Fermín festival.',
                'places_of_interest' => 'Plaza del Castillo, Ciudadela, Catedral de Pamplona, Museo de Navarra, Parque de la Taconera',
                'monuments' => 'Catedral de Santa María la Real, Ciudadela de Pamplona, Iglesia de San Nicolás, Ayuntamiento de Pamplona',
                'photos' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800'
                ]
            ],
            [
                'name' => 'Valencia',
                'address' => 'Valencia, Spain',
                'province' => 'Valencia',
                'description' => 'Valencia is a vibrant city on Spain\'s southeastern coast, famous for its Fallas festival and modern architecture.',
                'places_of_interest' => 'Ciudad de las Artes y las Ciencias, Mercado Central, Barrio del Carmen, Playa de la Malvarrosa, Jardín del Turia',
                'monuments' => 'Catedral de Valencia, Torres de Serranos, Lonja de la Seda, Iglesia de San Nicolás, Mercado Central',
                'photos' => [
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800',
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800'
                ]
            ],
            [
                'name' => 'Seville',
                'address' => 'Andalusia, Spain',
                'province' => 'Sevilla',
                'description' => 'Seville is the capital of Andalusia, known for its flamenco, Moorish architecture, and the famous Feria de Abril.',
                'places_of_interest' => 'Real Alcázar, Catedral de Sevilla, Barrio de Santa Cruz, Plaza de España, Parque de María Luisa',
                'monuments' => 'Catedral de Sevilla, Real Alcázar, Torre del Oro, Archivo de Indias, Iglesia del Salvador',
                'photos' => [
                    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                    'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=800'
                ]
            ]
        ];

        foreach ($localities as $locality) {
            Locality::create($locality);
        }
    }
}
