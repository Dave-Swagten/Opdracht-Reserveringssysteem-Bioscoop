<?php

namespace App\Models\Chairs;

class LuxuryChair extends AbstractChair
{
    public function getType(): string
    {
        return 'luxe';
    }

    public function calculatePrice(float $basePrice): float
    {
        return $basePrice + 5.00; // Luxe stoelen kosten 5 euro extra
    }
}
