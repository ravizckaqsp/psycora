<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user) {
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('guru_bk')) {
                return redirect()->route('guruBk.dashboard');
            } elseif ($user->hasRole('siswa')) {
                return redirect()->route('siswa.dashboard');
            }
        }

        return $next($request);
    }
}