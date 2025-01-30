<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Maak een nieuwe reservering
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'screening_id' => 'required|exists:screenings,id',
                'chair_ids' => 'required|array',
                'chair_ids.*' => 'exists:chairs,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255'
            ]);

            // Start een database transactie
            return DB::transaction(function () use ($validated) {
                // Controleer of de stoelen nog beschikbaar zijn
                $screening = Screening::findOrFail($validated['screening_id']);
                
                // Controleer of er al reserveringen bestaan voor deze stoelen en vertoning
                $existingReservations = Reservation::where('screening_id', $validated['screening_id'])
                    ->whereIn('chair_id', $validated['chair_ids'])
                    ->exists();

                if ($existingReservations) {
                    return response()->json([
                        'error' => 'Een of meerdere geselecteerde stoelen zijn helaas al gereserveerd.'
                    ], 422);
                }

                $reservations = [];
                $reservationCode = strtoupper(Str::random(8));

                // Maak reserveringen voor alle geselecteerde stoelen
                foreach ($validated['chair_ids'] as $chairId) {
                    $reservation = new Reservation();
                    $reservation->screening_id = $validated['screening_id'];
                    $reservation->chair_id = $chairId;
                    $reservation->name = $validated['name'];
                    $reservation->email = $validated['email'];
                    $reservation->reservation_code = $reservationCode; // Gebruik dezelfde code voor alle stoelen
                    $reservation->price = $screening->price;
                    $reservation->save();
                    
                    $reservations[] = $reservation;
                }

                return response()->json([
                    'message' => 'Reserveringen succesvol gemaakt!',
                    'reservation_code' => $reservationCode
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Er is een fout opgetreden: ' . $e->getMessage()
            ], 500);
        }
    }
}
