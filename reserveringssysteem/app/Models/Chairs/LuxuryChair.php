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
        return 15.00; // Premium prijs voor een luxe stoel
    }
}
