<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL; // ← tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {

    }


    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Force HTTPS di production
        if (app()->enviroment('production')){
            URL::forceScheme('https');
        }
    }
}