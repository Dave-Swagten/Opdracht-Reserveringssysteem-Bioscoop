<?php

namespace App\Models\Chairs;

use App\Models\Chair;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractChair extends Model implements ChairInterface
{
    protected $table = 'chairs';
    
    protected $fillable = [
        'type',
        'row_number',
        'seat_number',
        'is_available',
        'price'
    ];

    abstract public function getType(): string;
    abstract public function getPrice(): float;

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
