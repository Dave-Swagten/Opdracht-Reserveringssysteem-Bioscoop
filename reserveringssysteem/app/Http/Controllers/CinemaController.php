<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chairs\ChairFactory;
use App\Models\Chairs\StandardChair;

class CinemaController extends Controller
{
    public function index()
    {
        // Voor nu maken we een simpele 5x5 zaal
        $rows = 5;
        $seatsPerRow = 5;
        
        // Haal bestaande stoelen op of maak nieuwe aan
        $seats = [];
        for ($row = 1; $row <= $rows; $row++) {
            $seats[$row] = [];
            for ($seat = 1; $seat <= $seatsPerRow; $seat++) {
                // Zoek eerst of de stoel al bestaat
                $chair = StandardChair::where('row_number', $row)
                    ->where('seat_number', $seat)
                    ->first();
                
                // Als de stoel niet bestaat, maak een nieuwe aan
                if (!$chair) {
                    $chair = ChairFactory::createChair('standaard', $row, $seat);
                }
                
                $seats[$row][$seat] = $chair;
            }
        }
        
        return view('cinema.index', compact('seats'));
    }
}
