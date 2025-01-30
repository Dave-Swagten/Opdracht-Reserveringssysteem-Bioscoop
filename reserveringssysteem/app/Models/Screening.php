<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screening extends Model
{
    protected $fillable = [
        'movie_id',
        'screen_id',
        'start_time',
        'end_time',
        'price',
        'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * De film van deze vertoning
     */
    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * De zaal van deze vertoning
     */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    /**
     * De reserveringen voor deze vertoning
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
