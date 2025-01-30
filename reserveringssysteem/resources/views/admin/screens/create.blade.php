@extends('layouts.admin')

@section('title', 'Zaal Toevoegen')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Nieuwe Zaal</h2>

            <form action="{{ route('admin.screens.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <!-- Naam -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Bijvoorbeeld: Zaal 1">
                    </div>

                    <!-- Rijen -->
                    <div>
                        <label for="rows" class="block text-sm font-medium text-gray-700">Aantal rijen</label>
                        <input type="number" name="rows" id="rows" value="{{ old('rows') }}" required min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Stoelen per rij -->
                    <div>
                        <label for="seats_per_row" class="block text-sm font-medium text-gray-700">Stoelen per rij</label>
                        <input type="number" name="seats_per_row" id="seats_per_row" value="{{ old('seats_per_row') }}" required min="1"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Configuratie -->
                    <div>
                        <label for="configuration" class="block text-sm font-medium text-gray-700">Speciale stoelen configuratie</label>
                        <div class="mt-4 space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="has_luxury_seats" id="has_luxury_seats" value="1" {{ old('has_luxury_seats') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <label for="has_luxury_seats" class="ml-2 text-sm text-gray-600">
                                    Luxe stoelen (laatste rij)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="has_wheelchair_spots" id="has_wheelchair_spots" value="1" {{ old('has_wheelchair_spots') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <label for="has_wheelchair_spots" class="ml-2 text-sm text-gray-600">
                                    Rolstoelplekken (eerste rij hoeken)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="inline-flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Actief</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <a href="{{ route('admin.screens.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        Annuleren
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Zaal Toevoegen
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
