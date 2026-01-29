<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'festivity_id',
        'name',
        'description',
        'location',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function festivity(): BelongsTo
    {
        return $this->belongsTo(Festivity::class);
    }

    public function scopeOrderedChronologically($query)
    {
        return $query->orderByRaw('
            CASE 
                WHEN start_time IS NULL THEN 0 
                ELSE 1 
            END,
            start_time ASC
        ');
    }
}
