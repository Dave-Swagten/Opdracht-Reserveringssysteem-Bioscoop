<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Screening;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'rating',
        'poster_url',
        'is_active'
    ];

    protected $casts = [
        'duration' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * De vertoningen van deze film
     */
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }
}
