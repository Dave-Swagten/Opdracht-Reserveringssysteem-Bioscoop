<?php

namespace App\Models\Chairs;

class StandardChair extends AbstractChair
{
    public function getType(): string
    {
        return 'standaard';
    }

    public function getPrice(): float
    {
        return 10.00; // Standaard prijs voor een normale stoel
    }
}
