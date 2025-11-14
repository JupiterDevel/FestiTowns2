<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Festivity;
use App\Models\Locality;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $premium = $this->faker->boolean(60);
        $festivity = Festivity::inRandomOrder()->first();
        $localityId = $festivity?->locality_id ?? Locality::inRandomOrder()->value('id');

        return [
            'premium' => $premium,
            'name' => $premium ? $this->faker->company() : null,
            'url' => $premium ? $this->faker->url() : null,
            'image' => $premium ? "https://picsum.photos/seed/{$this->faker->uuid}/800/400" : null,
            'priority' => $this->faker->randomElement([
                Advertisement::PRIORITY_PRINCIPAL,
                Advertisement::PRIORITY_SECONDARY,
            ]),
            'festivity_id' => $festivity?->id,
            'locality_id' => $localityId,
            'start_date' => $premium && $festivity
                ? $festivity->start_date?->copy()->subDays(7)
                : null,
            'end_date' => $premium && $festivity
                ? $festivity->end_date
                : null,
            'active' => true,
        ];
    }
}
