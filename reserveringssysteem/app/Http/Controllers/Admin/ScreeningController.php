<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Screen;
use App\Models\Screening;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScreeningController extends Controller
{
    /**
     * Toon een lijst van alle vertoningen
     */
    public function index(): View
    {
        $screenings = Screening::with(['movie', 'screen'])
            ->orderBy('start_time')
            ->get();
        
        return view('admin.screenings.index', compact('screenings'));
    }

    /**
     * Toon het formulier om een nieuwe vertoning aan te maken
     */
    public function create(): View
    {
        $movies = Movie::where('is_active', true)->orderBy('title')->get();
        $screens = Screen::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.screenings.create', compact('movies', 'screens'));
    }

    /**
     * Sla een nieuwe vertoning op
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screen_id' => 'required|exists:screens,id',
            'start_time' => 'required|date|after:now',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        // Haal de film op voor de duur
        $movie = Movie::findOrFail($validated['movie_id']);
        
        // Bereken de eindtijd (start + duur van film)
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($movie->duration);

        // Controleer of er geen overlap is met andere vertoningen
        $hasOverlap = Screening::where('screen_id', $validated['screen_id'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($hasOverlap) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Er is al een vertoning gepland in deze zaal op dit tijdstip.']);
        }

        // Maak de vertoning aan
        Screening::create([
            'movie_id' => $validated['movie_id'],
            'screen_id' => $validated['screen_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $validated['price'],
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()
            ->route('admin.screenings.index')
            ->with('success', 'Vertoning succesvol aangemaakt.');
    }

    /**
     * Toon een specifieke vertoning
     */
    public function show(Screening $screening): View
    {
        $screening->load(['movie', 'screen']);
        return view('admin.screenings.show', compact('screening'));
    }

    /**
     * Toon het formulier om een vertoning te bewerken
     */
    public function edit(Screening $screening): View
    {
        $movies = Movie::where('is_active', true)->orderBy('title')->get();
        $screens = Screen::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.screenings.edit', compact('screening', 'movies', 'screens'));
    }

    /**
     * Update een specifieke vertoning
     */
    public function update(Request $request, Screening $screening)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'screen_id' => 'required|exists:screens,id',
            'start_time' => 'required|date',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean'
        ]);

        // Haal de film op voor de duur
        $movie = Movie::findOrFail($validated['movie_id']);
        
        // Bereken de eindtijd (start + duur van film)
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($movie->duration);

        // Controleer of er geen overlap is met andere vertoningen (behalve deze)
        $hasOverlap = Screening::where('screen_id', $validated['screen_id'])
            ->where('id', '!=', $screening->id)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($hasOverlap) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Er is al een vertoning gepland in deze zaal op dit tijdstip.']);
        }

        // Update de vertoning
        $screening->update([
            'movie_id' => $validated['movie_id'],
            'screen_id' => $validated['screen_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $validated['price'],
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()
            ->route('admin.screenings.index')
            ->with('success', 'Vertoning succesvol bijgewerkt.');
    }

    /**
     * Verwijder een specifieke vertoning
     */
    public function destroy(Screening $screening)
    {
        // Controleer of er geen reserveringen zijn
        if ($screening->reservations()->exists()) {
            return back()->with('error', 'Deze vertoning kan niet worden verwijderd omdat er al reserveringen zijn.');
        }

        $screening->delete();

        return redirect()
            ->route('admin.screenings.index')
            ->with('success', 'Vertoning succesvol verwijderd.');
    }
}
