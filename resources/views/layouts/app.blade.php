<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            @include('components.sidebar')

            <div class="flex-1 flex flex-col">
                <!-- Mobile top bar -->
                <div class="md:hidden bg-white border-b">
                    <div class="flex items-center justify-between px-4 py-2">
                        <a href="{{ route('dashboard') }}" class="text-lg font-bold text-indigo-600">Everi State</a>
                        <div>
                            <button id="mobile-menu-button" class="p-2 rounded bg-gray-100">
                                Menu
                            </button>
                        </div>
                    </div>
                </div>

                <main class="flex-1">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
                        @if (isset($header))
                            <div class="mb-4">{{ $header }}</div>
                        @elseif(View::hasSection('header'))
                            <div class="mb-4">@yield('header')</div>
                        @endif

                        @isset($slot)
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endisset
                    </div>
                </main>
            </div>
        </div>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            // Simple mobile menu toggle
            document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
                const sidebar = document.querySelector('aside');
                if (sidebar) sidebar.classList.toggle('hidden');
            });
        </script>
    </body>
</html>
