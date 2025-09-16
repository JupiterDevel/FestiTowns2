<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'province',
        'photo',
    ];

    // Relación 1:N con Holiday
    public function festive()
    {
        return $this->hasMany(Festive::class);
    }
}