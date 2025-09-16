<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Festive extends Model
{
    /** @use HasFactory<\Database\Factories\FestiveFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'date',
        'town_id',
    ];

    public function town()
    {
        return $this->belongsTo(Town::class);
    }
    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }
}
