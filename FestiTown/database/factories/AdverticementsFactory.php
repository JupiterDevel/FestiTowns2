<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adverticements>
 */
class AdverticementsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $advertisementImages = [
            'https://picsum.photos/800/600?random=' . rand(1, 1000),
            'https://picsum.photos/800/600?random=' . rand(1001, 2000),
            'https://picsum.photos/800/600?random=' . rand(2001, 3000),
            'https://picsum.photos/800/600?random=' . rand(3001, 4000),
            'https://picsum.photos/800/600?random=' . rand(4001, 5000),
        ];
        
        return [
            'image_url' => $this->faker->randomElement($advertisementImages),
            'festive_id' => \App\Models\Festive::factory(),
        ];
    }
}
