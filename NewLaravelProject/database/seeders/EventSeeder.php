<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Festivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $festivities = Festivity::all();

        if ($festivities->count() > 0) {
            // Crear eventos de ejemplo para las primeras festividades
            $sampleEvents = [
                [
                    'name' => 'Quema de Fallas',
                    'description' => 'El momento más esperado de las Fallas, donde se queman los monumentos falleros en una espectacular ceremonia.',
                    'location' => 'Plaza del Ayuntamiento',
                    'start_time' => now()->addDays(1)->setTime(9, 0),
                    'end_time' => now()->addDays(1)->setTime(13, 0),
                ],
                [
                    'name' => 'Almuerzo Fallero',
                    'description' => 'Comida tradicional fallera donde se reúnen las comisiones para celebrar juntos.',
                    'location' => 'Casa de la Fallera Mayor',
                    'start_time' => now()->addDays(1)->setTime(12, 0),
                    'end_time' => now()->addDays(1)->setTime(14, 0),
                ],
                [
                    'name' => 'Desfile de Trajes Regionales',
                    'description' => 'Desfile de trajes tradicionales de la región con música y danzas folclóricas.',
                    'location' => 'Calle Mayor',
                    'start_time' => now()->addDays(2)->setTime(17, 0),
                    'end_time' => now()->addDays(2)->setTime(19, 0),
                ],
                [
                    'name' => 'Concierto de Música Tradicional',
                    'description' => 'Concierto con grupos locales interpretando música tradicional de la zona.',
                    'location' => 'Plaza de la Iglesia',
                    'start_time' => now()->addDays(2)->setTime(20, 0),
                    'end_time' => now()->addDays(2)->setTime(22, 0),
                ],
                [
                    'name' => 'Actividad sin horario definido',
                    'description' => 'Esta es una actividad que no tiene horario específico y aparecerá al principio de la lista.',
                    'location' => 'Varios lugares',
                ],
            ];

            foreach ($festivities->take(3) as $festivity) {
                foreach ($sampleEvents as $eventData) {
                    Event::create([
                        'festivity_id' => $festivity->id,
                        'name' => $eventData['name'],
                        'description' => $eventData['description'],
                        'location' => $eventData['location'],
                        'start_time' => $eventData['start_time'] ?? null,
                        'end_time' => $eventData['end_time'] ?? null,
                    ]);
                }
            }
        }
    }
}
