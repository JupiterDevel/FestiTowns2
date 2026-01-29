<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Festivity;
use App\Models\Locality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $festivities = Festivity::with('locality')->get();
        $localities = Locality::all();

        if ($festivities->isEmpty() && $localities->isEmpty()) {
            return;
        }

        // Premium ads linked to festivities/localities
        if ($festivities->isNotEmpty()) {
            $premiumAdsToCreate = min(12, max(6, $festivities->count()));
            for ($i = 0; $i < $premiumAdsToCreate; $i++) {
                $festivity = $festivities->random();
                Advertisement::create([
                    'premium' => true,
                    'name' => 'Promo ' . Str::title(Str::limit($festivity->name, 20)),
                    'url' => 'https://example.com/promos/' . Str::slug($festivity->name) . '-' . $i,
                    'image' => 'https://picsum.photos/seed/ad-premium-' . $i . '/800/400',
                    'priority' => $i % 3 === 0
                        ? Advertisement::PRIORITY_PRINCIPAL
                        : Advertisement::PRIORITY_SECONDARY,
                    'festivity_id' => $festivity->id,
                    'locality_id' => $festivity->locality_id,
                    'active' => true,
                ]);
            }
        }

        // Default (non-premium) placeholder ads
        $defaultConfigurations = [
            ['priority' => Advertisement::PRIORITY_PRINCIPAL],
            ['priority' => Advertisement::PRIORITY_SECONDARY],
            ['priority' => Advertisement::PRIORITY_SECONDARY],
            ['priority' => Advertisement::PRIORITY_SECONDARY],
        ];

        foreach ($defaultConfigurations as $index => $config) {
            Advertisement::create([
                'premium' => false,
                'name' => null,
                'url' => null,
                'image' => null,
                'priority' => $config['priority'],
                'festivity_id' => null,
                'locality_id' => null,
                'start_date' => null,
                'end_date' => null,
                'active' => true,
            ]);
        }
    }
}
