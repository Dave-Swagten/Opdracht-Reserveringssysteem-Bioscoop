@extends('layouts.admin')

@section('title', 'Vertoning Bewerken')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Vertoning Bewerken</h2>

            <form action="{{ route('admin.screenings.update', $screening) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <!-- Film -->
                    <div>
                        <label for="movie_id" class="block text-sm font-medium text-gray-700">Film</label>
                        <select name="movie_id" id="movie_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Selecteer een film</option>
                            @foreach($movies as $movie)
                                <option value="{{ $movie->id }}" {{ old('movie_id', $screening->movie_id) == $movie->id ? 'selected' : '' }}>
                                    {{ $movie->title }} ({{ $movie->duration }} min)
                                </option>
                            @endforeach
                        </select>
                        @error('movie_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Zaal -->
                    <div>
                        <label for="screen_id" class="block text-sm font-medium text-gray-700">Zaal</label>
                        <select name="screen_id" id="screen_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Selecteer een zaal</option>
                            @foreach($screens as $screen)
                                <option value="{{ $screen->id }}" {{ old('screen_id', $screening->screen_id) == $screen->id ? 'selected' : '' }}>
                                    {{ $screen->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('screen_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Starttijd -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Starttijd</label>
                        <input type="datetime-local" name="start_time" id="start_time" 
                            value="{{ old('start_time', $screening->start_time->format('Y-m-d\TH:i')) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prijs -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Basis Ticketprijs</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">€</span>
                            </div>
                            <input type="number" name="price" id="price" step="0.01" min="0"
                                value="{{ old('price', $screening->price) }}"
                                class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $screening->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Actief
                        </label>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Vertoning Bijwerken
                    </button>
                    <a href="{{ route('admin.screenings.index') }}" class="ml-3 text-gray-600 hover:text-gray-900">
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
