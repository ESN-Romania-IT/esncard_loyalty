<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Body styiling because there is an offset of 20px that i don't know where it's coming from -->
    <style>
        body {
            translate: 0px -20px;
        }
    </style>
</head>

<body class="font-sans antialiased m-0">
    <div class="min-h-screen bg-black-100 dark:bg-black-900">
        <!-- Page Heading -->
        @isset($header)
            <header class="bg-[#EBEBEB] shadow mt-4">
                <div class="max-w-7xl mx-auto px-6 py-6 flex justify-center">
                    {{ $header }}
                </div>
            </header>
        @endisset

        @auth
            <div class="flex justify-end items-center gap-3 mr-10 mt-5">
                <a href="{{ route('me') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Logout
                    </button>
                </form>
            </div>
        @endauth


        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex justify-center bg-white">
            <div class="w-full ">
                {{ $slot }}
            </div>
        </main>

        {{-- FOOTER --}}
        @isset($footer)
            <footer class="bg-[#EBEBEB] shadow">
                <div class="mx-auto flex justify-center">
                    {{ $footer }}
                </div>
            </footer>
        @endisset

    </div>
</body>

</html>
