<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beech Bioscoop - Reserveren</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-center mb-8">Beech Bioscoop</h1>
        
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6">
            <div class="mb-8">
                <div class="w-full h-4 bg-gray-300 rounded-full mb-4"></div>
                <p class="text-center text-sm text-gray-600">Filmscherm</p>
            </div>
            
            <div class="grid gap-4">
                @foreach($seats as $row => $rowSeats)
                    <div class="flex justify-center gap-4">
                        @foreach($rowSeats as $seatNumber => $seat)
                            <button 
                                class="w-12 h-12 rounded-lg {{ $seat->isAvailable() ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500' }} 
                                       text-white font-bold flex items-center justify-center transition-colors"
                                {{ !$seat->isAvailable() ? 'disabled' : '' }}
                                data-row="{{ $row }}"
                                data-seat="{{ $seatNumber }}"
                            >
                                {{ $row }}-{{ $seatNumber }}
                            </button>
                        @endforeach
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
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
        </div>
    </div>

    <script>
        document.querySelectorAll('button[data-row]').forEach(button => {
            button.addEventListener('click', () => {
                const row = button.dataset.row;
                const seat = button.dataset.seat;
                alert(`Je hebt stoel ${row}-${seat} geselecteerd.`);
            });
        });
    </script>
</body>
</html>
