<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Einstellungen – Fotobox</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-950 text-white min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-sm">
        <h1 class="text-2xl font-bold text-center mb-8 tracking-wide">Einstellungen</h1>

        <form method="POST" action="{{ route('settings.authenticate') }}" class="space-y-4">
            @csrf

            <div>
                <input
                    type="password"
                    name="password"
                    placeholder="Passwort"
                    autofocus
                    class="w-full px-4 py-3 rounded-xl bg-gray-900 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-700' }} text-white placeholder-gray-500 focus:outline-none focus:border-gray-500 text-sm">

                @error('password')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full py-3 bg-white text-gray-950 font-semibold rounded-xl hover:bg-gray-200 active:scale-95 transition-all duration-150 text-sm">
                Anmelden
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('camera') }}" class="text-sm text-gray-600 hover:text-gray-400 transition-colors">
                ← Zurück zur Fotobox
            </a>
        </div>
    </div>

</body>
</html>
