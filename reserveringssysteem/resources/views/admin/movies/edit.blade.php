@extends('layouts.admin')

@section('title', 'Film Bewerken')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Film Bewerken</h2>

            <form action="{{ route('admin.movies.update', $movie) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Titel -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Titel</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $movie->title) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Beschrijving -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Beschrijving</label>
                        <textarea name="description" id="description" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $movie->description) }}</textarea>
                    </div>

                    <!-- Duur -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Duur (minuten)</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration', $movie->duration) }}" required min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Genre -->
                    <div>
                        <label for="genre" class="block text-sm font-medium text-gray-700">Genre</label>
                        <input type="text" name="genre" id="genre" value="{{ old('genre', $movie->genre) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Poster -->
                    <div>
                        <label for="poster_url" class="block text-sm font-medium text-gray-700">Poster URL</label>
                        <input type="url" name="poster_url" id="poster_url" value="{{ old('poster_url', $movie->poster_url) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @if($movie->poster_url)
                            <img src="{{ $movie->poster_url }}" alt="Huidige poster" class="mt-2 h-32 w-auto">
                        @endif
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="inline-flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $movie->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Actief</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('admin.movies.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        Annuleren
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Film Bijwerken
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
