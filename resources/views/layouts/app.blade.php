<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'SIPADI'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-bell text-gray-600 text-lg"></i>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <i class="fas fa-redo text-gray-600 text-lg"></i>
                        </button>
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                @endisset
            </main>
        </div>
    </body>
</html>
