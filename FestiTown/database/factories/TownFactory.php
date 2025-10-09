<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Town>
 */
class TownFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $spanishTowns = [
            'Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Palma', 'Las Palmas', 'Bilbao',
            'Alicante', 'Córdoba', 'Valladolid', 'Vigo', 'Gijón', 'Hospitalet', 'Vitoria', 'A Coruña', 'Granada', 'Elche',
            'Oviedo', 'Santa Cruz', 'Badalona', 'Cartagena', 'Terrassa', 'Jerez', 'Sabadell', 'Móstoles', 'Alcalá de Henares', 'Pamplona'
        ];
        
        $spanishProvinces = [
            'Madrid', 'Barcelona', 'Valencia', 'Sevilla', 'Zaragoza', 'Málaga', 'Murcia', 'Baleares', 'Las Palmas', 'Vizcaya',
            'Alicante', 'Córdoba', 'Valladolid', 'Pontevedra', 'Asturias', 'Álava', 'La Coruña', 'Granada', 'Murcia', 'Asturias',
            'Santa Cruz de Tenerife', 'Barcelona', 'Murcia', 'Barcelona', 'Cádiz', 'Barcelona', 'Madrid', 'Madrid', 'Navarra'
        ];

        $randomIndex = array_rand($spanishTowns);
        
        return [
            'name' => $spanishTowns[$randomIndex],
            'province' => $spanishProvinces[$randomIndex],
            'photo' => 'https://picsum.photos/400/300?random=' . rand(1, 1000),
        ];
    }
}
