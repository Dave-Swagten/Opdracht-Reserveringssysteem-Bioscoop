<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screen extends Model
{
    protected $fillable = [
        'name',
        'rows',
        'seats_per_row',
        'configuration',
        'is_active'
    ];

    protected $casts = [
        'rows' => 'integer',
        'seats_per_row' => 'integer',
        'configuration' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * De stoelen in deze zaal
     */
    public function chairs(): HasMany
    {
        return $this->hasMany(Chair::class);
    }

    /**
     * De vertoningen in deze zaal
     */
    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }

    /**
     * Controleer of de zaal luxe stoelen heeft
     */
    public function hasLuxurySeats(): bool
    {
        return $this->configuration['has_luxury_seats'] ?? false;
    }

    /**
     * Controleer of de zaal rolstoelplekken heeft
     */
    public function hasWheelchairSpots(): bool
    {
        return $this->configuration['has_wheelchair_spots'] ?? false;
    }
}
