<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Screen;

class Chair extends Model
{
    protected $fillable = [
        'screen_id',
        'type',
        'row_number',
        'seat_number',
        'price',
        'is_available'
    ];

    protected $casts = [
        'row_number' => 'integer',
        'seat_number' => 'integer',
        'price' => 'decimal:2',
        'is_available' => 'boolean'
    ];

    /**
     * De zaal waar deze stoel bij hoort
     */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }
}
