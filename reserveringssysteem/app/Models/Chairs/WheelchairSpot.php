<?php

namespace App\Models\Chairs;

class WheelchairSpot extends AbstractChair
{
    public function getType(): string
    {
        return 'rolstoel';
    }

    public function getPrice(): float
    {
        return 10.00; // Zelfde prijs als standaard stoel
    }
}
