<?php

namespace Database\Seeders;

use App\Models\Locality;
use App\Models\Festivity;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Array de nombres de localidades españolas
        $localities = [
            'Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Palma', 'Las Palmas', 'Bilbao',
            'Alicante', 'Córdoba', 'Valladolid', 'Vigo', 'Gijón', 'Hospitalet', 'Vitoria', 'A Coruña', 'Elche', 'Granada',
            'Terrassa', 'Badalona', 'Cartagena', 'Sabadell', 'Jerez', 'Móstoles', 'Santa Cruz', 'Pamplona', 'Almería', 'Fuenlabrada',
            'Leganés', 'San Sebastián', 'Burgos', 'Albacete', 'Getafe', 'Salamanca', 'Huelva', 'Logroño', 'Badajoz', 'San Fernando',
            'Huelva', 'León', 'Tarragona', 'Cádiz', 'Lérida', 'Marbella', 'Dos Hermanas', 'Mataró', 'Santa Coloma', 'Algeciras'
        ];

        // Crear 50 localidades
        foreach ($localities as $localityName) {
            Locality::create([
                'name' => $localityName,
                'address' => "Calle Principal, 1, {$localityName}",
                'description' => "Descripción de la localidad de {$localityName}. Una hermosa ciudad con rica historia y cultura.",
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
        
        for ($i = 0; $i < 200; $i++) {
            $locality = $localities->random();
            $startDate = now()->addDays(rand(-365, 365));
            $endDate = $startDate->copy()->addDays(rand(1, 7));
            
            Festivity::create([
                'locality_id' => $locality->id,
                'name' => $festivityNames[array_rand($festivityNames)] . ' de ' . $locality->name,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'description' => "Descripción de la festividad en {$locality->name}. Una celebración tradicional que atrae a visitantes de toda la región.",
                'photos' => $this->getRandomFestivityPhotos(2), // 2 fotos aleatorias
            ]);
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