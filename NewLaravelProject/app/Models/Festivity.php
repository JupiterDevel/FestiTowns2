<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Festivity extends Model
{
    protected $fillable = [
        'locality_id',
        'province',
        'name',
        'slug',
        'start_date',
        'end_date',
        'description',
        'photos',
        'latitude',
        'longitude',
        'google_maps_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($festivity) {
            if (empty($festivity->slug)) {
                $festivity->slug = static::generateUniqueSlug($festivity->name);
            }
        });

        static::updating(function ($festivity) {
            if ($festivity->isDirty('name') && empty($festivity->slug)) {
                $festivity->slug = static::generateUniqueSlug($festivity->name, $festivity->id);
            }
        });

        static::updated(function ($festivity) {
            if ($festivity->wasChanged(['start_date', 'end_date'])) {
                $festivity->advertisements()->get()->each(function ($advertisement) {
                    $advertisement->save();
                });
            }
        });
    }

    protected static function generateUniqueSlug($name, $id = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;
        
        $query = static::where('slug', $slug);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        // Keep incrementing until we find a unique slug
        while ($query->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            $query = static::where('slug', $slug);
            if ($id) {
                $query->where('id', '!=', $id);
            }
        }
        
        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'photos' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('approved', true);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderedChronologically();
    }

    public function getVotesCountAttribute(): int
    {
        return $this->votes()->count();
    }
}
