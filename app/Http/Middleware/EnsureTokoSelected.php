<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokoSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('toko_id')) {
            return redirect()->route('login')->with('warning', 'Silakan pilih toko terlebih dahulu.');
        }

        return $next($request);
    }
}
