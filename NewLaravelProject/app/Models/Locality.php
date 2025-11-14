<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Locality extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'address',
        'province',
        'description',
        'places_of_interest',
        'monuments',
        'photos',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($locality) {
            if (empty($locality->slug)) {
                $locality->slug = static::generateUniqueSlug($locality->name);
            }
        });

        static::updating(function ($locality) {
            if ($locality->isDirty('name') && empty($locality->slug)) {
                $locality->slug = static::generateUniqueSlug($locality->name, $locality->id);
            }
        });
    }

    protected static function generateUniqueSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $query = static::where('slug', $slug);
        
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        if ($query->exists()) {
            $slug .= '-' . ($query->count() + 1);
        }
        
        return $slug;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $casts = [
        'photos' => 'array',
    ];

    public function festivities(): HasMany
    {
        return $this->hasMany(Festivity::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }
}
