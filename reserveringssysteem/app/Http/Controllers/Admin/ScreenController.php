<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Models\Chair;
use App\Models\Chairs\ChairFactory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScreenController extends Controller
{
    /**
     * Toon een lijst van alle zalen
     */
    public function index(): View
    {
        $screens = Screen::orderBy('name')->get();
        return view('admin.screens.index', compact('screens'));
    }

    /**
     * Toon het formulier om een nieuwe zaal aan te maken
     */
    public function create(): View
    {
        return view('admin.screens.create');
    }

    /**
     * Sla een nieuwe zaal op en maak de stoelen aan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rows' => 'required|integer|min:1',
            'seats_per_row' => 'required|integer|min:1',
            'has_luxury_seats' => 'boolean',
            'has_wheelchair_spots' => 'boolean',
            'is_active' => 'boolean'
        ]);

        // Maak de zaal aan
        $screen = Screen::create([
            'name' => $validated['name'],
            'rows' => $validated['rows'],
            'seats_per_row' => $validated['seats_per_row'],
            'configuration' => [
                'has_luxury_seats' => $request->boolean('has_luxury_seats'),
                'has_wheelchair_spots' => $request->boolean('has_wheelchair_spots')
            ],
            'is_active' => $request->boolean('is_active')
        ]);

        // Maak de stoelen aan voor deze zaal
        for ($row = 1; $row <= $validated['rows']; $row++) {
            for ($seat = 1; $seat <= $validated['seats_per_row']; $seat++) {
                // Bepaal het type stoel
                $type = 'standaard';
                
                // Controleer of de checkbox is aangevinkt en of dit de laatste rij is
                if ($request->has('has_luxury_seats') && $row === (int) $validated['rows']) {
                    $type = 'luxe';
                } elseif ($request->has('has_wheelchair_spots') && $row === 1 && in_array($seat, [1, $validated['seats_per_row']])) {
                    $type = 'rolstoel';
                }

                // Maak de stoel aan via de factory
                ChairFactory::createChair($type, $row, $seat, $screen->id);
            }
        }

        return redirect()
            ->route('admin.screens.index')
            ->with('success', 'Zaal succesvol aangemaakt met stoelconfiguratie.');
    }

    /**
     * Toon een specifieke zaal
     */
    public function show(Screen $screen): View
    {
        return view('admin.screens.show', compact('screen'));
    }

    /**
     * Toon het formulier om een zaal te bewerken
     */
    public function edit(Screen $screen): View
    {
        return view('admin.screens.edit', compact('screen'));
    }

    /**
     * Update een specifieke zaal
     */
    public function update(Request $request, Screen $screen)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $screen->update([
            'name' => $validated['name'],
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()
            ->route('admin.screens.index')
            ->with('success', 'Zaal succesvol bijgewerkt.');
    }

    /**
     * Verwijder een specifieke zaal
     */
    public function destroy(Screen $screen)
    {
        // Controleer eerst of er geen actieve vertoningen zijn
        if ($screen->screenings()->where('start_time', '>', now())->exists()) {
            return back()->with('error', 'Deze zaal kan niet worden verwijderd omdat er nog toekomstige vertoningen zijn gepland.');
        }

        // Verwijder alle stoelen van deze zaal
        $screen->chairs()->delete();
        
        // Verwijder de zaal
        $screen->delete();

        return redirect()
            ->route('admin.screens.index')
            ->with('success', 'Zaal en bijbehorende stoelen succesvol verwijderd.');
    }
}
