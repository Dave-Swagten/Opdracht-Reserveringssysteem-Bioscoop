@extends('layouts.admin')

@section('title', 'Zaal Bewerken')

@section('content')
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Zaal Bewerken</h2>

            <form action="{{ route('admin.screens.update', $screen) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6">
                    <!-- Naam -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $screen->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Info over rijen en stoelen -->
                    <div class="bg-gray-50 p-4 rounded">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Zaal Configuratie</h3>
                        <p class="text-sm text-gray-600">Aantal rijen: {{ $screen->rows }}</p>
                        <p class="text-sm text-gray-600">Stoelen per rij: {{ $screen->seats_per_row }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            <i>De zaalconfiguratie kan niet worden aangepast na aanmaken. Maak een nieuwe zaal aan als je de configuratie wilt wijzigen.</i>
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="inline-flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $screen->is_active) ? 'checked' : '' }}
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
                        Zaal Bijwerken
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stoelconfiguratie Weergave -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Huidige Stoelconfiguratie</h3>
            
            <div class="grid gap-2 justify-center" style="grid-template-columns: repeat({{ $screen->seats_per_row }}, minmax(0, 1fr));">
                @foreach($screen->chairs->sortBy(['row_number', 'seat_number']) as $chair)
                    <div class="p-2 text-center rounded {{ 
                        match($chair->type) {
                            'luxury' => 'bg-purple-100 text-purple-800',
                            'wheelchair' => 'bg-blue-100 text-blue-800',
                            default => 'bg-gray-100 text-gray-800'
                        }
                    }}">
                        <span class="text-xs">R{{ $chair->row_number }}S{{ $chair->seat_number }}</span>
                        <br>
                        <span class="text-xs">€{{ number_format($chair->price, 2) }}</span>
                    </div>
                    @if($chair->seat_number == $screen->seats_per_row)
                        <div class="col-span-full h-2"></div>
                    @endif
                @endforeach
            </div>
            
            <div class="mt-4 flex justify-center space-x-4 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-100 rounded mr-2"></div>
                    <span>Standaard (€10)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-purple-100 rounded mr-2"></div>
                    <span>Luxe (€15)</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-100 rounded mr-2"></div>
                    <span>Rolstoel (€12)</span>
                </div>
            </div>
        </div>
    </div>
@endsection
