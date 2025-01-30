<?php

namespace App\Http\Controllers;

use App\Models\Chair;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'chair_id' => 'required|exists:chairs,id',
                'screening_time' => 'required|date',
                'movie_title' => 'required|string|max:255'
            ]);

            // Start een database transactie
            return DB::transaction(function () use ($validated) {
                // Haal de stoel op en check beschikbaarheid
                $chair = Chair::findOrFail($validated['chair_id']);
                
                if (!$chair->is_available) {
                    return response()->json([
                        'message' => 'Deze stoel is al gereserveerd.'
                    ], 400);
                }

                // Maak de reservering
                $reservation = Reservation::create($validated);
                
                // Update de stoel status
                $chair->update(['is_available' => false]);

                return response()->json([
                    'message' => 'Reservering succesvol gemaakt!',
                    'reservation' => $reservation
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Reserveringsfout: ' . $e->getMessage());
            return response()->json([
                'message' => 'Er is een fout opgetreden bij het maken van de reservering: ' . $e->getMessage()
            ], 500);
        }
    }
}
