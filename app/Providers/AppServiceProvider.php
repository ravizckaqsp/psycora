<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{
    // Redirect setelah login berdasarkan role
    \Illuminate\Support\Facades\URL::macro('homeForRole', function () {
        $user = Auth::user();
        if ($user?->hasRole('admin')) return '/admin/dashboard';
        if ($user?->hasRole('guru_bk')) return '/guru-bk/dashboard';
        return '/siswa/dashboard';
    });
}
}
