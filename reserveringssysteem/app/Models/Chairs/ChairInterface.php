<?php

namespace App\Models\Chairs;

interface ChairInterface
{
    public function getType(): string;
    public function calculatePrice(float $basePrice): float;
    public function isAvailable(): bool;
    public function reserve(): void;
    public function release(): void;
}
