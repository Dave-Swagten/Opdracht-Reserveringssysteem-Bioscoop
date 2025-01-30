<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Screen;
use App\Models\Screening;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class CinemaController extends Controller
{
    /**
     * Toon het filmoverzicht met vertoningen
     */
    public function index(): View
    {
        // Haal alle actieve films op met hun vertoningen
        $movies = Movie::where('is_active', true)
            ->with(['screenings' => function ($query) {
                $query->where('start_time', '>', now())
                    ->where('is_active', true)
                    ->orderBy('start_time')
                    ->with('screen');
            }])
            ->orderBy('title')
            ->get();

        return view('cinema.index', compact('movies'));
    }

    /**
     * Controleer beschikbaarheid van stoelen voor een vertoning
     */
    public function checkAvailability(Request $request)
    {
        try {
            $validated = $request->validate([
                'screening_id' => 'required|exists:screenings,id'
            ]);

            Log::info('Checking availability for screening: ' . $validated['screening_id']);

            // Haal de vertoning op met alle relaties
            $screening = Screening::with(['screen.chairs', 'movie', 'reservations'])
                ->findOrFail($validated['screening_id']);

            Log::info('Found screening: ' . $screening->id);
            Log::info('Screen chairs count: ' . $screening->screen->chairs->count());

            // Controleer of de vertoning nog niet is begonnen
            if ($screening->start_time <= now()) {
                return response()->json([
                    'error' => 'Deze vertoning is al begonnen.'
                ], 422);
            }

            // Haal alle stoelen op met hun beschikbaarheid
            $chairs = $screening->screen->chairs()
                ->orderBy('row_number')
                ->orderBy('seat_number')
                ->get()
                ->map(function ($chair) use ($screening) {
                    // Controleer of er een reservering bestaat voor deze stoel
                    $isReserved = $screening->reservations()
                        ->where('chair_id', $chair->id)
                        ->exists();

                    return [
                        'id' => $chair->id,
                        'type' => $chair->type,
                        'row_number' => $chair->row_number,
                        'seat_number' => $chair->seat_number,
                        'price' => $chair->price,
                        'is_available' => !$isReserved
                    ];
                })
                ->groupBy('row_number');

            Log::info('Grouped chairs count: ' . $chairs->count());

            // Bereken de prijs voor elk type stoel
            $prices = [
                'standaard' => (float) $screening->price,
                'luxe' => (float) $screening->price * 1.5,
                'rolstoel' => (float) $screening->price
            ];

            return response()->json([
                'screening' => [
                    'id' => $screening->id,
                    'movie' => $screening->movie->title,
                    'screen' => $screening->screen->name,
                    'start_time' => $screening->start_time->format('d-m-Y H:i'),
                    'end_time' => $screening->end_time->format('d-m-Y H:i'),
                ],
                'chairs' => $chairs,
                'prices' => $prices
            ]);

        } catch (\Exception $e) {
            Log::error('Error in checkAvailability: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'error' => 'Er is een fout opgetreden: ' . $e->getMessage()
            ], 500);
        }
    }
}
