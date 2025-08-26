<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('dashboardadmin.index');
            }

            if ($user->role === 'siswa') {
                if ($user->jurusan === 'RPL') {
                    return redirect()->route('dashboardrpl.index');
                } elseif ($user->jurusan === 'TKJ') {
                    return redirect()->route('dashboardsiswa.index');
                }
            }

            if ($user->role === 'pembimbing') {
                return redirect()->route('dashboardpembimbing.index');
            }
        }

        return $next($request);
    }
}
