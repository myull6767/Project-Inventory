<?php

namespace App\Providers;

use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (Auth::check() && session()->has('toko_id')) {
                $view->with('currentToko', Toko::find(session('toko_id')));
            }
        });
    }
}
