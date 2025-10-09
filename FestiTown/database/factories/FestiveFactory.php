<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Festive>
 */
class FestiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $festiveNames = [
            'Feria de Abril', 'San Fermín', 'Fallas de Valencia', 'Semana Santa', 'Carnaval de Cádiz',
            'La Tomatina', 'Fiesta de San Juan', 'Feria de Málaga', 'Fiesta de la Vendimia', 'Fiesta de la Virgen del Pilar',
            'Fiesta de San Isidro', 'Fiesta de la Mercè', 'Fiesta de San Sebastián', 'Fiesta de la Almudena',
            'Fiesta de San Antonio', 'Fiesta de la Candelaria', 'Fiesta de San Blas', 'Fiesta de San José',
            'Fiesta de la Inmaculada', 'Fiesta de San Nicolás', 'Fiesta de la Purísima', 'Fiesta de San Andrés'
        ];

        $currentYear = date('Y');
        $startDate = now()->startOfYear();
        $endDate = now()->endOfYear();
        
        return [
            'name' => $this->faker->randomElement($festiveNames),
            'date' => $this->faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
            'town_id' => \App\Models\Town::factory(),
        ];
    }
}
