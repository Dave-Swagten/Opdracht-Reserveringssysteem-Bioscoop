<?php

namespace App\Models\Chairs;

class WheelchairSpot extends AbstractChair
{
    public function getType(): string
    {
        return 'rolstoel';
    }

    public function calculatePrice(float $basePrice): float
    {
        return $basePrice; // Rolstoelplekken gebruiken de basisprijs
    }
}
