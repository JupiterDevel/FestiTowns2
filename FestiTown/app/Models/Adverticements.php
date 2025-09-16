<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adverticements extends Model
{
    /** @use HasFactory<\Database\Factories\AdverticementsFactory> */
    use HasFactory;
    protected $fillable = [
        'image_url',
        'festive_id',
    ];

    // Relación N:1 → cada anuncio pertenece a un festivo
    public function festive()
    {
        return $this->belongsTo(Festive::class);
    }
}
