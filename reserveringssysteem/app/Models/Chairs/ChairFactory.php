<?php

namespace App\Models\Chairs;

class ChairFactory
{
    public static function createChair(string $type, int $rowNumber, int $seatNumber): ChairInterface
    {
        return match ($type) {
            'standaard' => new StandardChair([
                'type' => 'standaard',
                'row_number' => $rowNumber,
                'seat_number' => $seatNumber,
                'price' => 10.00
            ]),
            'luxe' => new LuxuryChair([
                'type' => 'luxe',
                'row_number' => $rowNumber,
                'seat_number' => $seatNumber,
                'price' => 15.00
            ]),
            'rolstoel' => new WheelchairSpot([
                'type' => 'rolstoel',
                'row_number' => $rowNumber,
                'seat_number' => $seatNumber,
                'price' => 10.00
            ]),
            default => throw new \InvalidArgumentException("Ongeldig stoeltype: {$type}")
        };
    }
}
