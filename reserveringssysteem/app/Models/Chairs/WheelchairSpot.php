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
        return $this->getScreeningPrice(); // Gebruik de basisprijs van de screening
    }
}
