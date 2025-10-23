<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Festivity extends Model
{
    protected $fillable = [
        'locality_id',
        'province',
        'name',
        'start_date',
        'end_date',
        'description',
        'photos',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'photos' => 'array',
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

    public function events(): HasMany
    {
        return $this->hasMany(Event::class)->orderedChronologically();
    }

    public function getVotesCountAttribute(): int
    {
        return $this->votes()->count();
    }
}
