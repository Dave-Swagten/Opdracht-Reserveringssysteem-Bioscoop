<?php

namespace App\Http\Controllers;

use App\Models\Chair;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Chairs\ChairFactory;
use Carbon\Carbon;

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
                $chair = Chair::where('row_number', $row)
                    ->where('seat_number', $seat)
                    ->first();
                
                // Als de stoel niet bestaat, maak een nieuwe aan
                if (!$chair) {
                    $chair = ChairFactory::createChair('standaard', $row, $seat);
                }
                
                $seats[$row][$seat] = $chair;
            }
        }

        // Haal alle films en tijden op (in een echte applicatie zou dit uit de database komen)
        // TODO: Haal dit uit de database
        $screenings = [
            ['title' => 'Avatar 2', 'time' => '2025-01-30 20:00:00'],
            ['title' => 'Star Wars', 'time' => '2025-01-30 22:30:00']
        ];
        
        return view('cinema.index', compact('seats', 'screenings'));
    }

    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'movie_title' => 'required|string',
            'screening_time' => 'required|date'
        ]);

        // Haal alle bezette stoelen op voor deze film en tijd
        $occupiedChairIds = Reservation::where('movie_title', $validated['movie_title'])
            ->where('screening_time', $validated['screening_time'])
            ->pluck('chair_id');

        return response()->json([
            'occupied_chairs' => $occupiedChairIds
        ]);
    }
}
