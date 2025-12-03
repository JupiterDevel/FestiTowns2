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
        'is_adsense',
        'adsense_client_id',
        'adsense_slot_id',
        'adsense_type',
    ];

    protected $casts = [
        'premium' => 'boolean',
        'active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_adsense' => 'boolean',
    ];

    protected $attributes = [
        'priority' => self::PRIORITY_SECONDARY,
        'premium' => false,
        'active' => true,
        'is_adsense' => false,
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

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        // Si es una URL completa (http/https), devolverla tal cual
        if (\Illuminate\Support\Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        // Si es base64 (data:image...), devolverla tal cual
        if (\Illuminate\Support\Str::startsWith($this->image, 'data:image')) {
            return $this->image;
        }

        // Si parece ser base64 sin el prefijo data:, intentar detectarlo
        // Base64 válido generalmente tiene caracteres alfanuméricos, +, /, y = al final
        $base64Pattern = '/^[A-Za-z0-9+\/]+={0,2}$/';
        if (preg_match($base64Pattern, $this->image) && strlen($this->image) > 100) {
            try {
                // Intentar decodificar base64
                $decoded = base64_decode($this->image, true);
                if ($decoded !== false) {
                    // Intentar detectar el tipo MIME
                    $imageInfo = @getimagesizefromstring($decoded);
                    if ($imageInfo !== false && isset($imageInfo['mime'])) {
                        $mime = $imageInfo['mime'];
                        return 'data:' . $mime . ';base64,' . $this->image;
                    }
                    // Si no se puede detectar el MIME pero es binario válido, asumir JPEG
                    if (strlen($decoded) > 0) {
                        return 'data:image/jpeg;base64,' . $this->image;
                    }
                }
            } catch (\Exception $e) {
                // Si falla, continuar con el flujo normal
            }
        }

        // Si es una ruta de archivo, usar asset()
        return asset($this->image);
    }

    protected function syncDatesFromFestivity(): void
    {
        // Solo aplicar a anuncios premium
        if (!$this->premium) {
            return;
        }

        // Para anuncios premium: fecha de inicio = ahora, fecha de fin = 365 días después
        $this->start_date = now();
        $this->end_date = now()->addDays(365);
    }
}
