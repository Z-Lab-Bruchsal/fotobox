<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictToSubnet
{
    public function handle(Request $request, Closure $next, string $subnet = '192'): Response
    {
        if (!str_starts_with($request->ip(), $subnet) && !str_starts_with($request->ip(), "127")) {
            abort(403);
        }

        return $next($request);
    }
}
