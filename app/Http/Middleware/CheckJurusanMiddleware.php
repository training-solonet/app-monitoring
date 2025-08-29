<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckJurusanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$jurusans
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$jurusans)
    {
        if (Auth::check() && in_array(Auth::user()->jurusan, $jurusans)) {
            return $next($request);
        }

        return redirect()->route('sign-in');
    }
}
