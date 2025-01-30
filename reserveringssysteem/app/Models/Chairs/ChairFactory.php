<?php

namespace App\Models\Chairs;

class ChairFactory
{
    /**
     * Maak een nieuwe stoel aan en sla deze op in de database
     */
    public static function createChair(string $type, int $rowNumber, int $seatNumber, int $screenId): ChairInterface
    {
        $chair = self::createInstance($type);

        // Vul de eigenschappen in en sla op
        $chair->fill([
            'type' => $type,
            'row_number' => $rowNumber,
            'seat_number' => $seatNumber,
            'screen_id' => $screenId,
            'is_available' => true
        ]);
        
        $chair->save();
        
        return $chair;
    }

    /**
     * Maak een tijdelijke stoelinstantie voor prijsberekening
     */
    public static function createInstance(string $type): ChairInterface
    {
        return match ($type) {
            'standaard' => new StandardChair(),
            'luxe' => new LuxuryChair(),
            'rolstoel' => new WheelchairSpot(),
            default => throw new \InvalidArgumentException("Ongeldig stoeltype: {$type}")
        };
    }
}
