<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DataMining') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-['Poppins']">

    <header class="bg-gradient-to-r from-blue-500 to-blue-600 py-6 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="/" class="flex items-center">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" style="width: 100px; height: 100px;">
            </a>
            </a>
            </a>
        </div>
    </header>

    <main class="container mx-auto py-12">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 mx-auto bg-white shadow-lg overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
