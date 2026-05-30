<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsPassword
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('settings_authenticated')) {
            return redirect()->route('settings.login');
        }

        return $next($request);
    }
}
