<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fotobox – Anmelden</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col items-center justify-center p-6">

    <h1 class="text-3xl font-bold mb-8 tracking-wide">Fotobox</h1>

    <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm flex flex-col gap-4">
        @csrf

        <div class="flex flex-col gap-1">
            <label for="email" class="text-sm text-gray-400">E-Mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/40">
            @error('email')
                <span class="text-sm text-red-400">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex flex-col gap-1">
            <label for="password" class="text-sm text-gray-400">Passwort</label>
            <input id="password" type="password" name="password" required
                   class="rounded-xl bg-white/10 border border-white/20 px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/40">
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
            <input type="checkbox" name="remember" class="rounded">
            Angemeldet bleiben
        </label>

        <button type="submit"
                class="mt-2 px-10 py-4 bg-white text-gray-950 font-semibold text-lg rounded-full shadow-lg hover:bg-gray-200 active:scale-95 transition-all duration-150">
            Anmelden
        </button>
    </form>

</body>
</html>
