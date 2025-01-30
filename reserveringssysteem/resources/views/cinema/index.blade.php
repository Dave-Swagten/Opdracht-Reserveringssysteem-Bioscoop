<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Beech Bioscoop - Reserveren</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Beech Bioscoop</h1>
        
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <!-- Film selectie -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Film & Tijd</h2>
                <select id="movie" class="w-full p-2 border rounded">
                    @foreach($screenings as $screening)
                        <option value="{{ $screening['title'] }}" data-time="{{ $screening['time'] }}">
                            {{ $screening['title'] }} - {{ \Carbon\Carbon::parse($screening['time'])->format('H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Zaal layout -->
            <div class="mb-8">
                <div class="w-full h-4 bg-gray-300 rounded-full mb-4"></div>
                <p class="text-center text-sm text-gray-600">Filmscherm</p>
            </div>
            
            <div class="grid gap-4">
                @foreach($seats as $row => $rowSeats)
                    <div class="flex justify-center gap-4">
                        @foreach($rowSeats as $seatNumber => $seat)
                            <button 
                                class="seat-button w-12 h-12 rounded-lg bg-green-500 hover:bg-green-600
                                       text-white font-bold flex items-center justify-center transition-colors"
                                data-row="{{ $row }}"
                                data-seat="{{ $seatNumber }}"
                                data-chair-id="{{ $seat->id }}"
                            >
                                {{ $row }}-{{ $seatNumber }}
                            </button>
                        @endforeach
                    </div>
                @endforeach
            </div>
            
            <!-- Legenda -->
            <div class="mt-8 mb-8">
                <div class="flex justify-center gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span class="text-sm">Beschikbaar</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                        <span class="text-sm">Bezet</span>
                    </div>
                </div>
            </div>

            <!-- Reserveringsformulier -->
            <div id="reservationForm" class="hidden">
                <h2 class="text-xl font-semibold mb-4">Reservering maken</h2>
                <form id="bookingForm" class="space-y-4">
                    <input type="hidden" name="chair_id" id="chairId">
                    <input type="hidden" name="screening_time" id="screeningTime">
                    <input type="hidden" name="movie_title" id="movieTitle">
                    
                    <div>
                        <label class="block text-gray-700">Naam</label>
                        <input type="text" name="name" class="w-full p-2 border rounded" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full p-2 border rounded" required>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                            Reserveren
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // CSRF token voor AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Functie om stoelbeschikbaarheid bij te werken
        async function updateSeatAvailability() {
            const movie = document.getElementById('movie');
            const movieTitle = movie.value;
            const screeningTime = movie.selectedOptions[0].dataset.time;

            try {
                const response = await fetch('/check-availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        movie_title: movieTitle,
                        screening_time: screeningTime
                    })
                });

                const data = await response.json();
                
                // Reset alle stoelen naar beschikbaar
                document.querySelectorAll('.seat-button').forEach(button => {
                    button.classList.remove('bg-red-500', 'hover:bg-red-600');
                    button.classList.add('bg-green-500', 'hover:bg-green-600');
                    button.disabled = false;
                });

                // Markeer bezette stoelen
                data.occupied_chairs.forEach(chairId => {
                    const button = document.querySelector(`button[data-chair-id="${chairId}"]`);
                    if (button) {
                        button.classList.remove('bg-green-500', 'hover:bg-green-600');
                        button.classList.add('bg-red-500');
                        button.disabled = true;
                    }
                });
            } catch (error) {
                console.error('Fout bij het ophalen van stoelbeschikbaarheid:', error);
            }
        }

        // Update beschikbaarheid bij het laden en wanneer film/tijd verandert
        document.addEventListener('DOMContentLoaded', updateSeatAvailability);
        document.getElementById('movie').addEventListener('change', updateSeatAvailability);
        
        // Event listeners voor stoelselectie
        document.querySelectorAll('.seat-button').forEach(button => {
            button.addEventListener('click', () => {
                const chairId = button.dataset.chairId;
                const movie = document.getElementById('movie');
                const movieTitle = movie.value;
                const screeningTime = movie.selectedOptions[0].dataset.time;
                
                // Vul verborgen velden
                document.getElementById('chairId').value = chairId;
                document.getElementById('screeningTime').value = screeningTime;
                document.getElementById('movieTitle').value = movieTitle;
                
                // Toon formulier
                document.getElementById('reservationForm').classList.remove('hidden');
            });
        });
        
        // Handle form submission
        document.getElementById('bookingForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            
            try {
                const response = await fetch('/reservations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert(result.message);
                    // Update stoelbeschikbaarheid na succesvolle reservering
                    await updateSeatAvailability();
                    // Reset en verberg formulier
                    e.target.reset();
                    document.getElementById('reservationForm').classList.add('hidden');
                } else {
                    alert(result.message || 'Er is een fout opgetreden.');
                }
            } catch (error) {
                alert('Er is een fout opgetreden bij het maken van de reservering.');
            }
        });
    </script>
</body>
</html>
