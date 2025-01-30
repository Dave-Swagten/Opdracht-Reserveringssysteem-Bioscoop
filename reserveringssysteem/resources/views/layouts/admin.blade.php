<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Beech Bioscoop</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.movies.index') }}" class="font-bold text-xl">
                        Beech Admin
                    </a>
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('admin.movies.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.movies.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            Films
                        </a>
                        <a href="{{ route('admin.screens.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.screens.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            Zalen
                        </a>
                        <a href="{{ route('admin.screenings.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.screenings.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            Vertoningen
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
