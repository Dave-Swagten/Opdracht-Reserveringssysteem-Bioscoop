<?php

namespace App\Models\Chairs;

class StandardChair extends AbstractChair
{
    public function getType(): string
    {
        return 'standaard';
    }

    public function calculatePrice(float $basePrice): float
    {
        return $basePrice; // Standaard stoelen gebruiken de basisprijs
    }
}
