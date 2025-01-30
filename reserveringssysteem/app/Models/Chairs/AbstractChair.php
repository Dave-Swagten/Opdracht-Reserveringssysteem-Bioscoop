<?php

namespace App\Models\Chairs;

use App\Models\Chair;
use App\Models\Screen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class AbstractChair extends Model implements ChairInterface
{
    protected $table = 'chairs';
    
    protected $fillable = [
        'type',
        'row_number',
        'seat_number',
        'screen_id',
        'is_available',
        'price'
    ];

    protected $casts = [
        'row_number' => 'integer',
        'seat_number' => 'integer',
        'screen_id' => 'integer',
        'is_available' => 'boolean',
        'price' => 'float'
    ];

    abstract public function getType(): string;
    abstract public function getPrice(): float;

    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    public function isAvailable(): bool
    {
        return $this->is_available;
    }

    public function reserve(): void
    {
        $this->is_available = false;
        $this->save();
    }

    public function release(): void
    {
        $this->is_available = true;
        $this->save();
    }
}
