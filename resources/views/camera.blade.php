<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fotobox</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col items-center justify-center p-6">

    <h1 class="text-3xl font-bold mb-8 tracking-wide">Fotobox</h1>

    @livewire('camera-capture')

    @livewire('photo-settings')

    @livewireScripts
</body>
</html>
