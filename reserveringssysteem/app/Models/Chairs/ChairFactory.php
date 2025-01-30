<?php

namespace App\Models\Chairs;

class ChairFactory
{
    public static function createChair(string $type, int $rowNumber, int $seatNumber, int $screenId): ChairInterface
    {
        $chair = match ($type) {
            'standaard' => new StandardChair(),
            'luxe' => new LuxuryChair(),
            'rolstoel' => new WheelchairSpot(),
            default => throw new \InvalidArgumentException("Ongeldig stoeltype: {$type}")
        };

        // Vul de eigenschappen in en sla op
        $chair->fill([
            'type' => $type,
            'row_number' => $rowNumber,
            'seat_number' => $seatNumber,
            'screen_id' => $screenId,
            'price' => $chair->getPrice(),
            'is_available' => true
        ]);
        
        $chair->save();
        
        return $chair;
    }
}
