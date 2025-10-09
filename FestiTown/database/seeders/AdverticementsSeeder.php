<?php

namespace Database\Seeders;

use App\Models\Adverticements;
use App\Models\Festive;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdverticementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create advertisements for each festive
        $festives = Festive::all();
        
        foreach ($festives as $festive) {
            // Create 2-4 advertisements per festive
            $adCount = rand(2, 4);
            
            for ($i = 0; $i < $adCount; $i++) {
                Adverticements::create([
                    'image_url' => 'https://picsum.photos/800/600?random=' . rand(1000, 9999),
                    'festive_id' => $festive->id,
                ]);
            }
        }
    }
}
