<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\Festivity;
use App\Models\Locality;
use Illuminate\Database\Eloquent\Collection;

class AdvertisementService
{
    public function forFestivity(Festivity $festivity): array
    {
        $locality = $festivity->relationLoaded('locality')
            ? $festivity->locality
            : $festivity->locality()->first();

        return $this->resolve($locality, $festivity);
    }

    public function forLocality(Locality $locality): array
    {
        return $this->resolve($locality);
    }

    protected function resolve(?Locality $locality = null, ?Festivity $festivity = null): array
    {
        $festivityIds = collect();
        if ($festivity) {
            $festivityIds->push($festivity->id);
        } elseif ($locality) {
            $festivityIds = $locality->festivities()->pluck('id');
        }

        $localityIds = collect();
        if ($locality) {
            $localityIds->push($locality->id);
        } elseif ($festivity?->locality_id) {
            $localityIds->push($festivity->locality_id);
        }

        $contextFestivities = $festivityIds->unique()->all();
        $contextLocalities = $localityIds->unique()->all();

        $main = $this->fetchSingleSlot(true, Advertisement::PRIORITY_PRINCIPAL, $contextFestivities, $contextLocalities);
        if (!$main) {
            $main = $this->fetchSingleSlot(false, Advertisement::PRIORITY_PRINCIPAL, $contextFestivities, $contextLocalities)
                ?? $this->placeholder(Advertisement::PRIORITY_PRINCIPAL);
        }

        $secondary = $this->fetchMultipleSlots(
            true,
            Advertisement::PRIORITY_SECONDARY,
            2,
            $contextFestivities,
            $contextLocalities,
            $main ? [$main->id] : []
        );

        if ($secondary->count() < 2) {
            $needed = 2 - $secondary->count();
            $exclude = $secondary->pluck('id')->filter()->all();
            if ($main && $main->id) {
                $exclude[] = $main->id;
            }
            $fallback = $this->fetchMultipleSlots(
                false,
                Advertisement::PRIORITY_SECONDARY,
                $needed,
                $contextFestivities,
                $contextLocalities,
                $exclude
            );
            $secondary = $secondary->concat($fallback);
        }

        while ($secondary->count() < 2) {
            $secondary->push($this->placeholder(Advertisement::PRIORITY_SECONDARY, $secondary->count()));
        }

        return [
            'main' => $main,
            'secondary' => $secondary->take(2),
        ];
    }

    protected function fetchSingleSlot(bool $premium, string $priority, array $festivityIds, array $localityIds, array $excludeIds = []): ?Advertisement
    {
        return $this->buildBaseQuery($premium, $priority, $festivityIds, $localityIds, $excludeIds)
            ->first();
    }

    protected function fetchMultipleSlots(
        bool $premium,
        string $priority,
        int $limit,
        array $festivityIds,
        array $localityIds,
        array $excludeIds = []
    ): Collection {
        return $this->buildBaseQuery($premium, $priority, $festivityIds, $localityIds, $excludeIds)
            ->limit($limit)
            ->get();
    }

    protected function buildBaseQuery(
        bool $premium,
        string $priority,
        array $festivityIds,
        array $localityIds,
        array $excludeIds
    ) {
        $query = Advertisement::query()
            ->when($premium, fn ($q) => $q->premium()->currentlyValid(), fn ($q) => $q->default())
            ->active()
            ->forContext($festivityIds, $localityIds)
            ->where('priority', $priority)
            ->inRandomOrder();

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query;
    }

    protected function placeholder(string $priority, int $index = 0): Advertisement
    {
        return new Advertisement([
            'premium' => false,
            'priority' => $priority,
            'name' => null,
            'url' => null,
            'image' => null,
            'active' => true,
        ]);
    }
}

