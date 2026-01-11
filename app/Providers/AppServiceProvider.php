<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Tambahkan ini agar kode lebih rapi

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
        /**
         * Memaksa Laravel menggunakan skema HTTPS saat berada di lingkungan produksi (Railway).
         * Ini akan memperbaiki error "419 Page Expired" dan peringatan "Informasi tidak aman".
         */
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}