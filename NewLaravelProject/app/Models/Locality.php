<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locality extends Model
{
    protected $fillable = [
        'name',
        'address',
        'description',
        'places_of_interest',
        'monuments',
        'photos',
    ];

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
}
