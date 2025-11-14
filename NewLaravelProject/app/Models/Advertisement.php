<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Advertisement extends Model
{
    /** @use HasFactory<\Database\Factories\AdvertisementFactory> */
    use HasFactory;

    public const PRIORITY_PRINCIPAL = 'principal';
    public const PRIORITY_SECONDARY = 'secondary';

    protected $fillable = [
        'premium',
        'name',
        'url',
        'image',
        'priority',
        'festivity_id',
        'locality_id',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'premium' => 'boolean',
        'active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $attributes = [
        'priority' => self::PRIORITY_SECONDARY,
        'premium' => false,
        'active' => true,
    ];

    protected static function booted(): void
    {
        static::saving(function (Advertisement $advertisement) {
            $advertisement->syncDatesFromFestivity();
        });
    }

    public function festivity(): BelongsTo
    {
        return $this->belongsTo(Festivity::class);
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }

    public function scopePremium(Builder $query): Builder
    {
        return $query->where('premium', true);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('premium', false);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeCurrentlyValid(Builder $query): Builder
    {
        $now = now()->startOfMinute();

        return $query->where(function (Builder $dateQuery) use ($now) {
            $dateQuery->whereNull('start_date')->orWhereDate('start_date', '<=', $now);
        })->where(function (Builder $dateQuery) use ($now) {
            $dateQuery->whereNull('end_date')->orWhereDate('end_date', '>=', $now);
        });
    }

    public function scopeForContext(Builder $query, array $festivityIds = [], array $localityIds = []): Builder
    {
        if (empty($festivityIds) && empty($localityIds)) {
            return $query;
        }

        return $query->where(function (Builder $contextQuery) use ($festivityIds, $localityIds) {
            $hasCondition = false;

            if (!empty($festivityIds)) {
                $contextQuery->whereIn('festivity_id', $festivityIds);
                $hasCondition = true;
            }

            if (!empty($localityIds)) {
                if ($hasCondition) {
                    $contextQuery->orWhereIn('locality_id', $localityIds);
                } else {
                    $contextQuery->whereIn('locality_id', $localityIds);
                    $hasCondition = true;
                }
            }

            $contextQuery->orWhere(function (Builder $generalQuery) {
                $generalQuery->whereNull('festivity_id')->whereNull('locality_id');
            });
        });
    }

    public function isExpired(): bool
    {
        return !is_null($this->end_date) && $this->end_date->isBefore(now());
    }

    protected function syncDatesFromFestivity(): void
    {
        if (!$this->festivity_id) {
            return;
        }

        $festivity = $this->relationLoaded('festivity')
            ? $this->festivity
            : Festivity::select(['id', 'start_date', 'end_date'])->find($this->festivity_id);

        if (!$festivity) {
            return;
        }

        $this->start_date = Carbon::parse($festivity->start_date)->subDays(7);
        $this->end_date = Carbon::parse($festivity->end_date);
    }
}
