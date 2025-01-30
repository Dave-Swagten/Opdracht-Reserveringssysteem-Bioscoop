<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bioscoop Reserveringssysteem</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <h1 class="text-3xl font-bold text-center text-gray-900">Films & Vertoningen</h1>

        <!-- Films overzicht -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($movies as $movie)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $movie->title }}</h2>
                        <p class="text-gray-600 mb-4">{{ $movie->description }}</p>
                        <p class="text-sm text-gray-500 mb-4">Duur: {{ $movie->duration }} minuten</p>
                        
                        @if($movie->screenings->isNotEmpty())
                            <div class="space-y-2">
                                <h3 class="font-semibold text-gray-900">Vertoningen:</h3>
                                @foreach($movie->screenings as $screening)
                                    <button 
                                        class="screening-button w-full text-left px-4 py-2 bg-blue-50 hover:bg-blue-100 rounded-md text-sm"
                                        data-screening-id="{{ $screening->id }}"
                                    >
                                        {{ $screening->start_time->format('d-m-Y H:i') }} - 
                                        {{ $screening->screen->name }} - 
                                        €{{ number_format($screening->price, 2) }}
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Geen vertoningen beschikbaar</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Stoelenselectie -->
        <div id="seatSelection" class="hidden space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-lg space-y-4">
                <h3 class="text-xl font-bold text-gray-900">Selecteer uw stoelen</h3>
                
                <!-- Legenda -->
                <div class="flex gap-4 justify-center text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded"></div>
                        <span>Standaard</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-purple-500 rounded"></div>
                        <span>Luxe</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span>Rolstoel</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-gray-500 rounded"></div>
                        <span>Bezet</span>
                    </div>
                </div>

                <!-- Stoelengrid -->
                <div id="seatsGrid" class="space-y-2"></div>

                <!-- Geselecteerde stoelen -->
                <div class="mt-4 space-y-2">
                    <h4 class="font-semibold">Geselecteerde stoelen:</h4>
                    <div id="selectedSeats" class="space-y-2">
                        <p class="text-gray-500">Geen stoelen geselecteerd</p>
                    </div>
                    <p id="totalPrice" class="font-bold text-right">Totaal: €0.00</p>
                </div>
            </div>

            <!-- Reserveringsformulier -->
            <div id="reservationForm" class="hidden bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Reservering maken</h3>
                <form id="bookingForm" class="space-y-4">
                    <input type="hidden" id="chairIdInput" name="chair_id">
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                        <input type="text" id="name" name="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" id="email" name="email" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <button type="submit" id="submitReservation" disabled
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        Reserveren
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentScreening = null;
        let currentPrices = null;
        let selectedSeats = [];
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Functie om stoel te selecteren/deselecteren
        function selectSeat(button) {
            const chairId = button.dataset.chairId;
            const index = selectedSeats.findIndex(seat => seat.id === chairId);
            
            if (index === -1) {
                // Voeg stoel toe aan selectie
                selectedSeats.push({
                    id: chairId,
                    row: button.dataset.rowNumber,
                    seat: button.dataset.seatNumber,
                    price: parseFloat(button.dataset.price),
                    type: button.dataset.type
                });
                button.classList.add('selected');
            } else {
                // Verwijder stoel uit selectie
                selectedSeats.splice(index, 1);
                button.classList.remove('selected');
            }
            
            updateSelectedSeats();
        }

        // Update geselecteerde stoelen weergave
        function updateSelectedSeats() {
            const selectedSeatsContainer = document.getElementById('selectedSeats');
            const totalPriceElement = document.getElementById('totalPrice');
            const submitButton = document.getElementById('submitReservation');
            
            if (selectedSeats.length > 0) {
                // Toon geselecteerde stoelen
                selectedSeatsContainer.innerHTML = selectedSeats
                    .map(seat => `
                        <div class="flex items-center justify-between p-2 bg-gray-100 rounded">
                            <span>Rij ${seat.row}, Stoel ${seat.seat}</span>
                            <span>€${seat.price.toFixed(2)}</span>
                        </div>
                    `)
                    .join('');
                
                // Bereken en toon totaalprijs
                const totalPrice = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
                totalPriceElement.textContent = `Totaal: €${totalPrice.toFixed(2)}`;
                
                // Voeg chair_id toe aan verborgen input
                const chairIdInput = document.getElementById('chairIdInput');
                chairIdInput.value = selectedSeats[0].id; // We gebruiken voorlopig alleen de eerste stoel
                
                // Toon reserveringsformulier
                document.getElementById('reservationForm').classList.remove('hidden');
                submitButton.disabled = false;
            } else {
                selectedSeatsContainer.innerHTML = '<p class="text-gray-500">Geen stoelen geselecteerd</p>';
                totalPriceElement.textContent = 'Totaal: €0.00';
                document.getElementById('reservationForm').classList.add('hidden');
                submitButton.disabled = true;
            }
        }

        // Functie om stoelen te laden voor een vertoning
        async function loadSeats(screeningId) {
            try {
                console.log('Loading seats for screening:', screeningId);
                const response = await fetch(`/check-availability?screening_id=${screeningId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();
                console.log('Received data:', data);
                
                if (!response.ok) {
                    throw new Error(data.error || 'Er is een fout opgetreden bij het laden van de stoelen.');
                }
                
                if (data.error) {
                    throw new Error(data.error);
                }

                currentScreening = data.screening;
                currentPrices = {
                    standaard: parseFloat(data.prices.standaard),
                    luxe: parseFloat(data.prices.luxe),
                    rolstoel: parseFloat(data.prices.rolstoel)
                };

                // Reset selectie
                selectedSeats = [];
                updateSelectedSeats();

                // Maak stoelengrid
                const seatsGrid = document.getElementById('seatsGrid');
                seatsGrid.innerHTML = '';

                // Converteer object naar array en sorteer op rijnummer
                Object.entries(data.chairs)
                    .sort(([rowA], [rowB]) => parseInt(rowA) - parseInt(rowB))
                    .forEach(([rowNumber, chairs]) => {
                        const rowDiv = document.createElement('div');
                        rowDiv.className = 'flex justify-center gap-4 mb-4';
                        
                        chairs.forEach(chair => {
                            const button = document.createElement('button');
                            button.className = `
                                seat-button w-12 h-12 rounded-lg text-white font-bold 
                                flex items-center justify-center transition-colors
                                ${getChairClass(chair)}
                            `;
                            button.disabled = !chair.is_available;
                            button.dataset.chairId = chair.id;
                            button.dataset.rowNumber = chair.row_number;
                            button.dataset.seatNumber = chair.seat_number;
                            button.dataset.price = chair.price;
                            button.dataset.type = chair.type;
                            button.textContent = `${chair.row_number}-${chair.seat_number}`;
                            
                            if (chair.is_available) {
                                button.addEventListener('click', () => selectSeat(button));
                            }
                            
                            rowDiv.appendChild(button);
                        });
                        
                        seatsGrid.appendChild(rowDiv);
                    });

                // Toon stoelenselectie
                document.getElementById('seatSelection').classList.remove('hidden');
            } catch (error) {
                console.error('Fout bij laden stoelen:', error);
                alert('Er is een fout opgetreden bij het laden van de stoelen: ' + error.message);
            }
        }

        // Functie om de juiste CSS classes voor stoelen te bepalen
        function getChairClass(chair) {
            let classes = '';
            
            if (!chair.is_available) {
                classes += 'bg-gray-500 cursor-not-allowed ';
            } else {
                switch (chair.type) {
                    case 'standaard':
                        classes += 'bg-blue-500 hover:bg-blue-600 ';
                        break;
                    case 'luxe':
                        classes += 'bg-purple-500 hover:bg-purple-600 ';
                        break;
                    case 'rolstoel':
                        classes += 'bg-green-500 hover:bg-green-600 ';
                        break;
                }
            }
            
            return classes;
        }

        // Handle form submission
        document.getElementById('bookingForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            
            // Voeg alle geselecteerde stoelen toe aan de formData
            formData.delete('chair_id'); // Verwijder enkele stoel input als die bestaat
            selectedSeats.forEach(seat => {
                formData.append('chair_ids[]', seat.id);
            });

            // Voeg screening_id toe aan de formData
            formData.append('screening_id', currentScreening.id);

            try {
                const response = await fetch('/reservations', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Er is een fout opgetreden bij het maken van de reservering.');
                }

                if (data.error) {
                    throw new Error(data.error);
                }

                // Reset formulier en selectie
                selectedSeats = [];
                updateSelectedSeats();
                form.reset();
                
                // Toon succes bericht
                alert('Reservering succesvol gemaakt! Uw reserveringscode is: ' + data.reservation_code);
                
                // Herlaad stoelen
                await loadSeats(currentScreening.id);
            } catch (error) {
                console.error('Fout bij maken reservering:', error);
                alert(error.message);
            }
        });

        // Event listeners voor vertoning selectie
        document.querySelectorAll('.screening-button').forEach(button => {
            button.addEventListener('click', async () => {
                const screeningId = button.dataset.screeningId;
                await loadSeats(screeningId);
            });
        });
    </script>

    <style>
        .seat-button.selected {
            @apply ring-2 ring-yellow-400 ring-offset-2;
        }
    </style>
</body>
</html>
