<?php

namespace Database\Seeders;

use App\Models\Locality;
use App\Models\Festivity;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Array de localidades españolas con sus provincias y coordenadas
     */
    private $localitiesData = [
            ['name' => 'Madrid', 'province' => 'Madrid', 'lat' => 40.4168, 'lng' => -3.7038],
            ['name' => 'Barcelona', 'province' => 'Barcelona', 'lat' => 41.3851, 'lng' => 2.1734],
            ['name' => 'Valencia', 'province' => 'Valencia', 'lat' => 39.4699, 'lng' => -0.3763],
            ['name' => 'Sevilla', 'province' => 'Sevilla', 'lat' => 37.3891, 'lng' => -5.9845],
            ['name' => 'Zaragoza', 'province' => 'Zaragoza', 'lat' => 41.6488, 'lng' => -0.8891],
            ['name' => 'Málaga', 'province' => 'Málaga', 'lat' => 36.7213, 'lng' => -4.4214],
            ['name' => 'Murcia', 'province' => 'Murcia', 'lat' => 37.9922, 'lng' => -1.1307],
            ['name' => 'Palma', 'province' => 'Islas Baleares', 'lat' => 39.5696, 'lng' => 2.6502],
            ['name' => 'Las Palmas', 'province' => 'Las Palmas', 'lat' => 28.1248, 'lng' => -15.4300],
            ['name' => 'Bilbao', 'province' => 'Vizcaya', 'lat' => 43.2627, 'lng' => -2.9253],
            ['name' => 'Alicante', 'province' => 'Alicante', 'lat' => 38.3452, 'lng' => -0.4810],
            ['name' => 'Córdoba', 'province' => 'Córdoba', 'lat' => 37.8882, 'lng' => -4.7794],
            ['name' => 'Valladolid', 'province' => 'Valladolid', 'lat' => 41.6523, 'lng' => -4.7245],
            ['name' => 'Vigo', 'province' => 'Pontevedra', 'lat' => 42.2406, 'lng' => -8.7207],
            ['name' => 'Gijón', 'province' => 'Asturias', 'lat' => 43.5322, 'lng' => -5.6611],
            ['name' => 'Hospitalet', 'province' => 'Barcelona', 'lat' => 41.3597, 'lng' => 2.1003],
            ['name' => 'Vitoria', 'province' => 'Álava', 'lat' => 42.8467, 'lng' => -2.6716],
            ['name' => 'A Coruña', 'province' => 'La Coruña', 'lat' => 43.3623, 'lng' => -8.4115],
            ['name' => 'Elche', 'province' => 'Alicante', 'lat' => 38.2660, 'lng' => -0.6983],
            ['name' => 'Granada', 'province' => 'Granada', 'lat' => 37.1773, 'lng' => -3.5986],
            ['name' => 'Terrassa', 'province' => 'Barcelona', 'lat' => 41.5639, 'lng' => 2.0084],
            ['name' => 'Badalona', 'province' => 'Barcelona', 'lat' => 41.4500, 'lng' => 2.2470],
            ['name' => 'Cartagena', 'province' => 'Murcia', 'lat' => 37.6000, 'lng' => -0.9864],
            ['name' => 'Sabadell', 'province' => 'Barcelona', 'lat' => 41.5433, 'lng' => 2.1094],
            ['name' => 'Jerez', 'province' => 'Cádiz', 'lat' => 36.6866, 'lng' => -6.1370],
            ['name' => 'Móstoles', 'province' => 'Madrid', 'lat' => 40.3228, 'lng' => -3.8646],
            ['name' => 'Santa Cruz', 'province' => 'Santa Cruz de Tenerife', 'lat' => 28.4636, 'lng' => -16.2518],
            ['name' => 'Pamplona', 'province' => 'Navarra', 'lat' => 42.8181, 'lng' => -1.6443],
            ['name' => 'Almería', 'province' => 'Almería', 'lat' => 36.8381, 'lng' => -2.4597],
            ['name' => 'Fuenlabrada', 'province' => 'Madrid', 'lat' => 40.2842, 'lng' => -3.7946],
            ['name' => 'Leganés', 'province' => 'Madrid', 'lat' => 40.3272, 'lng' => -3.7636],
            ['name' => 'San Sebastián', 'province' => 'Guipúzcoa', 'lat' => 43.3183, 'lng' => -1.9812],
            ['name' => 'Burgos', 'province' => 'Burgos', 'lat' => 42.3439, 'lng' => -3.6969],
            ['name' => 'Albacete', 'province' => 'Albacete', 'lat' => 38.9942, 'lng' => -1.8584],
            ['name' => 'Getafe', 'province' => 'Madrid', 'lat' => 40.3057, 'lng' => -3.7329],
            ['name' => 'Salamanca', 'province' => 'Salamanca', 'lat' => 40.9701, 'lng' => -5.6635],
            ['name' => 'Huelva', 'province' => 'Huelva', 'lat' => 37.2614, 'lng' => -6.9447],
            ['name' => 'Logroño', 'province' => 'La Rioja', 'lat' => 42.4627, 'lng' => -2.4449],
            ['name' => 'Badajoz', 'province' => 'Badajoz', 'lat' => 38.8782, 'lng' => -6.9706],
            ['name' => 'San Fernando', 'province' => 'Cádiz', 'lat' => 36.4667, 'lng' => -6.1980],
            ['name' => 'León', 'province' => 'León', 'lat' => 42.5987, 'lng' => -5.5671],
            ['name' => 'Tarragona', 'province' => 'Tarragona', 'lat' => 41.1189, 'lng' => 1.2445],
            ['name' => 'Cádiz', 'province' => 'Cádiz', 'lat' => 36.5270, 'lng' => -6.2886],
            ['name' => 'Lérida', 'province' => 'Lérida', 'lat' => 41.6176, 'lng' => 0.6200],
            ['name' => 'Marbella', 'province' => 'Málaga', 'lat' => 36.5102, 'lng' => -4.8860],
            ['name' => 'Dos Hermanas', 'province' => 'Sevilla', 'lat' => 37.2836, 'lng' => -5.9209],
            ['name' => 'Mataró', 'province' => 'Barcelona', 'lat' => 41.5400, 'lng' => 2.4444],
            ['name' => 'Santa Coloma', 'province' => 'Barcelona', 'lat' => 41.4515, 'lng' => 2.2080],
            ['name' => 'Algeciras', 'province' => 'Cádiz', 'lat' => 36.1275, 'lng' => -5.4539],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear localidades con provincias
        foreach ($this->localitiesData as $localityData) {
            Locality::create([
                'name' => $localityData['name'],
                'address' => "Calle Principal, 1, {$localityData['name']}",
                'province' => $localityData['province'],
                'description' => "Descripción de la localidad de {$localityData['name']}. Una hermosa ciudad con rica historia y cultura.",
                'places_of_interest' => "Plaza Mayor, Catedral, Museo Municipal, Parque Central, Mercado de Abastos",
                'monuments' => "Monumento a los Héroes, Iglesia de San Pedro, Castillo Medieval, Ayuntamiento Histórico",
                'photos' => $this->getRandomPhotos(3), // 3 fotos aleatorias
            ]);
        }

        // Array de nombres de festividades
        $festivityNames = [
            'Feria de Abril', 'San Fermín', 'Las Fallas', 'Semana Santa', 'Carnavales', 'Fiestas de la Virgen',
            'Festival de Jazz', 'Feria del Libro', 'Fiesta de la Vendimia', 'Día de la Patrona',
            'Festival de Teatro', 'Feria Gastronómica', 'Fiesta de San Juan', 'Festival de Música',
            'Feria de Artesanía', 'Fiesta de la Primavera', 'Festival de Danza', 'Feria Medieval',
            'Fiesta de la Cosecha', 'Festival de Cine', 'Feria de la Tapa', 'Fiesta de la Tradición',
            'Festival de Flamenco', 'Feria de la Cerveza', 'Fiesta de los Patios', 'Festival de Folclore',
            'Feria de Antigüedades', 'Fiesta de la Vendimia', 'Festival de Rock', 'Feria de la Moda',
            'Fiesta de San Pedro', 'Festival de Ópera', 'Feria de la Cerámica', 'Fiesta de la Tradición',
            'Festival de Poesía', 'Feria de la Miel', 'Fiesta de San Antonio', 'Festival de Blues',
            'Feria de la Lana', 'Fiesta de la Cosecha', 'Festival de Marionetas', 'Feria del Queso',
            'Fiesta de la Vendimia', 'Festival de Salsa', 'Feria de la Madera', 'Fiesta de San Miguel',
            'Festival de Reggae', 'Feria de la Sal', 'Fiesta de la Tradición', 'Festival de Country'
        ];

        // Crear 200 festividades
        $localities = Locality::all();
        
        // Mapeo de nombres de localidades a coordenadas (para festividades)
        $localityCoordinates = [];
        foreach ($this->localitiesData as $loc) {
            $localityCoordinates[$loc['name']] = [
                'lat' => $loc['lat'],
                'lng' => $loc['lng'],
                'province' => $loc['province']
            ];
        }
        
        for ($i = 0; $i < 200; $i++) {
            $locality = $localities->random();
            $startDate = now()->addDays(rand(-365, 365));
            $endDate = $startDate->copy()->addDays(rand(1, 7));
            
            // Obtener coordenadas de la localidad
            $coords = $localityCoordinates[$locality->name] ?? null;
            
            // Generar URL de Google Maps si hay coordenadas
            $googleMapsUrl = null;
            if ($coords) {
                $googleMapsUrl = "https://www.google.com/maps/place/{$locality->name}," . urlencode($coords['province']) . "/@{$coords['lat']},{$coords['lng']},14z";
            }
            
            // Generar nombre único para evitar slugs duplicados
            $festivityName = $festivityNames[array_rand($festivityNames)] . ' de ' . $locality->name;
            // Agregar un número aleatorio si es necesario para hacer el nombre único
            if (rand(1, 100) > 80) { // 20% de probabilidad de agregar un sufijo
                $festivityName .= ' ' . rand(2024, 2026);
            }
            
            try {
                Festivity::create([
                    'locality_id' => $locality->id,
                    'province' => $coords['province'] ?? $locality->province,
                    'name' => $festivityName,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'description' => "Descripción de la festividad en {$locality->name}. Una celebración tradicional que atrae a visitantes de toda la región.",
                    'latitude' => $coords['lat'] ?? null,
                    'longitude' => $coords['lng'] ?? null,
                    'google_maps_url' => $googleMapsUrl,
                    'photos' => $this->getRandomFestivityPhotos(2), // 2 fotos aleatorias
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // Si hay error de slug duplicado, reintentar con un nombre diferente
                if (str_contains($e->getMessage(), 'slug')) {
                    $festivityName .= ' ' . uniqid();
                    Festivity::create([
                        'locality_id' => $locality->id,
                        'province' => $coords['province'] ?? $locality->province,
                        'name' => $festivityName,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'description' => "Descripción de la festividad en {$locality->name}. Una celebración tradicional que atrae a visitantes de toda la región.",
                        'latitude' => $coords['lat'] ?? null,
                        'longitude' => $coords['lng'] ?? null,
                        'google_maps_url' => $googleMapsUrl,
                        'photos' => $this->getRandomFestivityPhotos(2),
                    ]);
                } else {
                    throw $e;
                }
            }
        }
    }

    private function getRandomPhotos(int $count): array
    {
        $photoUrls = [
            'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1501594907352-04cda38ebc29?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1439066615861-d1af74d74000?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?w=800&h=600&fit=crop'
        ];

        return array_slice($photoUrls, 0, $count);
    }

    private function getRandomFestivityPhotos(int $count): array
    {
        $festivityPhotoUrls = [
            'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800&h=600&fit=crop',
            'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&h=600&fit=crop'
        ];

        return array_slice($festivityPhotoUrls, 0, $count);
    }

}