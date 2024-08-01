<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()->roles == 'manager') {

            return $next($request);
        }

        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'); // Ganti '/' dengan halaman yang sesuai
    }
}


