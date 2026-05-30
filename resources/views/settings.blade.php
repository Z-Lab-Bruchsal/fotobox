<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Einstellungen – Fotobox</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col items-center p-6">

    <div class="w-full max-w-2xl">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-2xl font-bold tracking-wide">Einstellungen</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('camera') }}" class="text-sm text-gray-500 hover:text-gray-300 transition-colors">
                    ← Fotobox
                </a>
                <form method="POST" action="{{ route('settings.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-red-400 transition-colors">
                        Abmelden
                    </button>
                </form>
            </div>
        </div>

        @livewire('photo-settings')
    </div>

    @livewireScripts
</body>
</html>
