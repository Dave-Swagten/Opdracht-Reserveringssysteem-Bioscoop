<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bioscoop Reserveringssysteem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/animations/scale.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen p-8 text-gray-100">
    <div class="max-w-7xl mx-auto space-y-8">
        <h1 class="text-4xl font-bold text-center bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-400 mb-12">
            Films & Vertoningen
        </h1>

        <!-- Films overzicht -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($movies as $movie)
                <div class="bg-gray-800 rounded-xl shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl border border-gray-700">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-100 mb-2">{{ $movie->title }}</h2>
                        <p class="text-gray-400 mb-4">{{ $movie->description }}</p>
                        <p class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-clock mr-2"></i>{{ $movie->duration }} minuten
                        </p>
                        
                        @if($movie->screenings->isNotEmpty())
                            <div class="space-y-3">
                                <h3 class="font-semibold text-gray-300">
                                    <i class="fas fa-calendar-alt mr-2"></i>Vertoningen:
                                </h3>
                                @foreach($movie->screenings as $screening)
                                    <button 
                                        class="screening-button w-full text-left px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm transition-all duration-200 flex items-center justify-between group"
                                        data-screening-id="{{ $screening->id }}"
                                    >
                                        <span class="flex items-center">
                                            <i class="fas fa-film mr-2 text-blue-400 group-hover:rotate-12 transition-transform duration-200"></i>
                                            {{ $screening->start_time->format('d-m-Y H:i') }}
                                        </span>
                                        <span class="flex items-center">
                                            <span class="mr-4 text-gray-400">{{ $screening->screen->name }}</span>
                                            <span class="text-green-400">€{{ number_format($screening->price, 2) }}</span>
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm italic">
                                <i class="fas fa-info-circle mr-2"></i>Geen vertoningen beschikbaar
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Stoelenselectie -->
        <div id="seatSelection" class="hidden space-y-6 animate-fade-in">
            <div class="bg-gray-800 p-8 rounded-xl shadow-xl border border-gray-700 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-100">
                        <i class="fas fa-chair mr-2 text-blue-400"></i>Selecteer uw stoelen
                    </h3>
                    <div id="screeningInfo" class="text-gray-400 text-sm"></div>
                </div>
                
                <!-- Legenda -->
                <div class="flex gap-6 justify-center text-sm bg-gray-700 p-4 rounded-lg">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-blue-500 rounded transition-transform hover:scale-110"></div>
                        <span>Standaard</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-purple-500 rounded transition-transform hover:scale-110"></div>
                        <span>Luxe</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded transition-transform hover:scale-110"></div>
                        <span>Rolstoel</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-gray-500 rounded transition-transform hover:scale-110"></div>
                        <span>Bezet</span>
                    </div>
                </div>

                <!-- Filmscherm -->
                <div class="relative">
                    <div class="w-full h-4 bg-gradient-to-r from-blue-400 via-purple-400 to-blue-400 rounded-lg mb-8 shadow-lg">
                        <div class="absolute -bottom-6 w-full text-center text-sm text-gray-400">Filmscherm</div>
                    </div>
                </div>

                <!-- Stoelengrid -->
                <div id="seatsGrid" class="space-y-3"></div>

                <!-- Geselecteerde stoelen -->
                <div class="mt-6 space-y-4">
                    <h4 class="font-semibold text-gray-300">
                        <i class="fas fa-shopping-cart mr-2 text-blue-400"></i>Geselecteerde stoelen:
                    </h4>
                    <div id="selectedSeats" class="space-y-2">
                        <p class="text-gray-500 italic">Geen stoelen geselecteerd</p>
                    </div>
                    <p id="totalPrice" class="font-bold text-right text-2xl text-green-400">Totaal: €0.00</p>
                </div>
            </div>

            <!-- Reserveringsformulier -->
            <div id="reservationForm" class="hidden animate-slide-up">
                <div class="bg-gray-800 p-8 rounded-xl shadow-xl border border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-100 mb-6">
                        <i class="fas fa-ticket-alt mr-2 text-blue-400"></i>Reservering maken
                    </h3>
                    <form id="bookingForm" class="space-y-6">
                        <input type="hidden" id="chairIdInput" name="chair_id">
                        
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-300">
                                <i class="fas fa-user mr-2 text-blue-400"></i>Naam
                            </label>
                            <input type="text" id="name" name="name" required
                                class="w-full h-12 px-4 bg-gray-700 border-2 border-gray-600 rounded-lg shadow-sm 
                                       focus:border-blue-400 focus:ring-blue-400 text-gray-100 text-lg
                                       placeholder-gray-400">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-300">
                                <i class="fas fa-envelope mr-2 text-blue-400"></i>E-mail
                            </label>
                            <input type="email" id="email" name="email" required
                                class="w-full h-12 px-4 bg-gray-700 border-2 border-gray-600 rounded-lg shadow-sm 
                                       focus:border-blue-400 focus:ring-blue-400 text-gray-100 text-lg
                                       placeholder-gray-400">
                        </div>
                        
                        <button type="submit" id="submitReservation" disabled
                            class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-3 rounded-lg font-semibold
                                   hover:from-blue-600 hover:to-purple-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 
                                   focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200
                                   transform hover:scale-[1.02]">
                            <i class="fas fa-check mr-2"></i>Reserveren
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentScreening = null;
        let currentPrices = null;
        let selectedSeats = [];
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Initialiseer tooltips
        function initTooltips() {
            tippy('[data-tippy-content]', {
                animation: 'scale',
                theme: 'dark',
                placement: 'top'
            });
        }

        // Functie om stoel te selecteren/deselecteren
        function selectSeat(button) {
            const chairId = button.dataset.chairId;
            const index = selectedSeats.findIndex(seat => seat.id === chairId);
            const indicator = button.querySelector('div');
            
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
                button.classList.add('scale-110');
                indicator.classList.remove('opacity-0');
                indicator.classList.add('opacity-30');
            } else {
                // Verwijder stoel uit selectie
                selectedSeats.splice(index, 1);
                button.classList.remove('selected');
                button.classList.remove('scale-110');
                indicator.classList.add('opacity-0');
                indicator.classList.remove('opacity-30');
            }
            
            updateSelectedSeats();
        }

        // Update geselecteerde stoelen weergave
        function updateSelectedSeats() {
            const selectedSeatsContainer = document.getElementById('selectedSeats');
            const totalPriceElement = document.getElementById('totalPrice');
            const submitButton = document.getElementById('submitReservation');
            const reservationForm = document.getElementById('reservationForm');
            
            if (selectedSeats.length > 0) {
                // Toon geselecteerde stoelen
                selectedSeatsContainer.innerHTML = selectedSeats
                    .map(seat => `
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg transform transition-all duration-200 hover:scale-[1.01]">
                            <span>
                                <i class="fas fa-chair mr-2 text-blue-400"></i>
                                Rij ${seat.row}, Stoel ${seat.seat}
                                <span class="text-sm text-gray-400 ml-2">(${seat.type})</span>
                            </span>
                            <span class="text-green-400">€${seat.price.toFixed(2)}</span>
                        </div>
                    `)
                    .join('');
                
                // Bereken en toon totaalprijs
                const totalPrice = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
                totalPriceElement.textContent = `Totaal: €${totalPrice.toFixed(2)}`;
                
                // Voeg alle geselecteerde stoelen toe aan de formData
                // Voeg screening_id toe aan de formData
                reservationForm.classList.remove('hidden');
                submitButton.disabled = false;
            } else {
                selectedSeatsContainer.innerHTML = '<p class="text-gray-500 italic"><i class="fas fa-info-circle mr-2"></i>Geen stoelen geselecteerd</p>';
                totalPriceElement.textContent = 'Totaal: €0.00';
                reservationForm.classList.add('hidden');
                submitButton.disabled = true;
            }
        }

        // Functie om stoelen te laden voor een vertoning
        async function loadSeats(screeningId) {
            try {
                const response = await fetch(`/check-availability?screening_id=${screeningId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.error || 'Er is een fout opgetreden bij het laden van de stoelen.');
                }
                
                if (data.error) {
                    throw new Error(data.error);
                }

                currentScreening = data.screening;
                currentPrices = data.prices;

                // Update screening info
                document.getElementById('screeningInfo').innerHTML = `
                    <div class="space-x-4">
                        <span><i class="fas fa-film mr-1"></i>${data.screening.movie}</span>
                        <span><i class="fas fa-door-open mr-1"></i>${data.screening.screen}</span>
                        <span><i class="fas fa-clock mr-1"></i>${data.screening.start_time}</span>
                    </div>
                `;

                // Reset selectie
                selectedSeats = [];
                updateSelectedSeats();

                // Toon stoelenselectie
                const seatSelection = document.getElementById('seatSelection');
                seatSelection.classList.remove('hidden');

                // Bouw stoelengrid
                const seatsGrid = document.getElementById('seatsGrid');
                seatsGrid.innerHTML = '';

                Object.entries(data.chairs).forEach(([rowNumber, seats]) => {
                    const rowDiv = document.createElement('div');
                    rowDiv.className = 'flex justify-center gap-2';
                    
                    seats.forEach(seat => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.disabled = !seat.is_available;
                        button.dataset.chairId = seat.id;
                        button.dataset.rowNumber = seat.row_number;
                        button.dataset.seatNumber = seat.seat_number;
                        button.dataset.price = seat.price;
                        button.dataset.type = seat.type;
                        
                        // Bepaal de stijl op basis van het type en beschikbaarheid
                        const baseClasses = 'w-10 h-10 rounded-lg transition-all duration-200 transform hover:scale-110 flex items-center justify-center relative';
                        const typeClasses = {
                            'standaard': 'bg-blue-500 hover:bg-blue-600',
                            'luxe': 'bg-purple-500 hover:bg-purple-600',
                            'rolstoel': 'bg-green-500 hover:bg-green-600'
                        };
                        
                        button.className = seat.is_available
                            ? `${baseClasses} ${typeClasses[seat.type]}`
                            : `${baseClasses} bg-gray-600 cursor-not-allowed opacity-50`;

                        // Voeg selectie indicator toe
                        const indicator = document.createElement('div');
                        indicator.className = 'absolute inset-0 bg-yellow-400 opacity-0 transition-opacity duration-200 rounded-lg';
                        button.appendChild(indicator);

                        // Voeg een tooltip toe
                        button.setAttribute('data-tippy-content', `
                            Rij ${seat.row_number}, Stoel ${seat.seat_number}
                            Type: ${seat.type}
                            Prijs: €${seat.price}
                            ${!seat.is_available ? '(Bezet)' : ''}
                        `);

                        // Voeg een icoontje toe op basis van het type
                        const icon = document.createElement('i');
                        icon.className = {
                            'standaard': 'fas fa-chair',
                            'luxe': 'fas fa-couch',
                            'rolstoel': 'fas fa-wheelchair'
                        }[seat.type];
                        button.appendChild(icon);

                        if (seat.is_available) {
                            button.onclick = () => selectSeat(button);
                        }
                        
                        rowDiv.appendChild(button);
                    });
                    
                    seatsGrid.appendChild(rowDiv);
                });

                // Initialiseer tooltips voor de nieuwe stoelen
                initTooltips();

            } catch (error) {
                console.error('Error:', error);
                showError(error.message);
            }
        }

        // Functie om een error popup te tonen
        function showError(message) {
            Swal.fire({
                title: 'Oeps!',
                text: message,
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3B82F6',
                background: '#1F2937',
                color: '#F3F4F6'
            });
        }

        // Functie om een succes popup te tonen
        function showSuccess(message, reservationCode) {
            Swal.fire({
                title: 'Gelukt!',
                html: `
                    <p class="mb-4">${message}</p>
                    <div class="bg-gray-700 p-4 rounded-lg mb-4">
                        <p class="text-gray-300 mb-2">Uw reserveringscode:</p>
                        <p class="text-2xl font-bold text-blue-400">${reservationCode}</p>
                    </div>
                    <p class="text-sm text-gray-400">Bewaar deze code goed!</p>
                `,
                icon: 'success',
                confirmButtonText: 'Sluiten',
                confirmButtonColor: '#3B82F6',
                background: '#1F2937',
                color: '#F3F4F6'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }

        // Functie om een loading popup te tonen
        function showLoading(message) {
            Swal.fire({
                title: message,
                allowOutsideClick: false,
                showConfirmButton: false,
                background: '#1F2937',
                color: '#F3F4F6',
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Event listeners
        document.querySelectorAll('.screening-button').forEach(button => {
            button.addEventListener('click', () => {
                // Verwijder actieve status van alle knoppen
                document.querySelectorAll('.screening-button').forEach(btn => {
                    btn.classList.remove('ring-2', 'ring-blue-400', 'ring-offset-2', 'ring-offset-gray-800');
                });
                
                // Voeg actieve status toe aan geklikte knop
                button.classList.add('ring-2', 'ring-blue-400', 'ring-offset-2', 'ring-offset-gray-800');
                
                loadSeats(button.dataset.screeningId);
            });
        });

        document.getElementById('bookingForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            const submitButton = document.getElementById('submitReservation');
            const originalText = submitButton.innerHTML;
            
            try {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Reservering verwerken...';
                showLoading('Reservering wordt verwerkt...');
                
                const form = event.target;
                const formData = new FormData(form);
                
                // Voeg alle geselecteerde stoelen toe aan de formData
                formData.delete('chair_id'); // Verwijder enkele stoel input als die bestaat
                selectedSeats.forEach(seat => {
                    formData.append('chair_ids[]', seat.id);
                });

                // Voeg screening_id toe aan de formData
                formData.append('screening_id', currentScreening.id);

                const response = await fetch('/reservations', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
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

                // Toon succes bericht met reserveringscode
                showSuccess('Uw reservering is succesvol verwerkt!', data.reservation_code);
                
            } catch (error) {
                console.error('Error:', error);
                showError(error.message);
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slide-up {
            from { 
                transform: translateY(20px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }

        .animate-slide-up {
            animation: slide-up 0.5s ease-out;
        }

        .seat-button.selected {
            @apply ring-2 ring-yellow-400 ring-offset-2 ring-offset-gray-800 scale-110;
        }
    </style>
</body>
</html>
