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
        return $this->getScreeningPrice(); // Gebruik de basisprijs van de screening
    }
}
