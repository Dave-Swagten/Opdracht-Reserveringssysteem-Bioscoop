<?php

namespace App\Models\Chairs;

class LuxuryChair extends AbstractChair
{
    public function getType(): string
    {
        return 'luxe';
    }

    public function getPrice(): float
    {
        return $this->getScreeningPrice() + 5.00; // Basisprijs plus 5 euro voor luxe stoel
    }
}
