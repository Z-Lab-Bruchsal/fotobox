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
<body class="bg-gray-950 text-white h-screen flex flex-col items-center p-6 overflow-hidden">

    <h1 class="shrink-0 text-3xl font-bold mb-8 tracking-wide">Fotobox</h1>

    <div class="flex-1 min-h-0 w-full flex flex-col items-center">
        @livewire('camera-capture')
    </div>

    @livewireScripts
</body>
</html>
