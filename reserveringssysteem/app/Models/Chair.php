<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Reservation;

class Chair extends Model
{
    protected $fillable = [
        'type',
        'row_number',
        'seat_number',
        'is_available',
        'price'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2'
    ];

    /**
     * De huidige reservering voor deze stoel
     */
    public function reservation(): HasOne
    {
        return $this->hasOne(Reservation::class);
    }
}
