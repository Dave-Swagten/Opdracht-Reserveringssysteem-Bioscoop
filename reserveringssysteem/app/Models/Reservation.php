<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Chair;

class Reservation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'chair_id',
        'screening_time',
        'movie_title'
    ];

    protected $casts = [
        'screening_time' => 'datetime'
    ];

    /**
     * De stoel die bij deze reservering hoort
     */
    public function chair(): BelongsTo
    {
        return $this->belongsTo(Chair::class);
    }
}
