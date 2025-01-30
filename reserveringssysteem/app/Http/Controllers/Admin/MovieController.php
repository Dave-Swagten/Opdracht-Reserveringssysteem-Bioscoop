<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    /**
     * Toon een lijst van alle films
     */
    public function index(): View
    {
        $movies = Movie::orderBy('title')->get();
        return view('admin.movies.index', compact('movies'));
    }

    /**
     * Toon het formulier om een nieuwe film aan te maken
     */
    public function create(): View
    {
        return view('admin.movies.create');
    }

    /**
     * Sla een nieuwe film op
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'genre' => 'required|string|max:255',
            'poster_url' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ]);

        // Zorg ervoor dat is_active correct wordt gezet
        $validated['is_active'] = $request->boolean('is_active');

        Movie::create($validated);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film succesvol toegevoegd.');
    }

    /**
     * Toon een specifieke film
     */
    public function show(Movie $movie): View
    {
        return view('admin.movies.show', compact('movie'));
    }

    /**
     * Toon het formulier om een film te bewerken
     */
    public function edit(Movie $movie): View
    {
        return view('admin.movies.edit', compact('movie'));
    }

    /**
     * Update een specifieke film
     */
    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'genre' => 'required|string|max:255',
            'poster_url' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ]);

        // Zorg ervoor dat is_active correct wordt gezet
        $validated['is_active'] = $request->boolean('is_active');

        $movie->update($validated);

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film succesvol bijgewerkt.');
    }

    /**
     * Verwijder een specifieke film
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()
            ->route('admin.movies.index')
            ->with('success', 'Film succesvol verwijderd.');
    }
}
