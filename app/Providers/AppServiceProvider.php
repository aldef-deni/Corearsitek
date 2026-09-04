<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

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
        // Bawaan Laravel memakai kelas Tailwind, sedangkan situs ini pakai CSS sendiri.
        Paginator::defaultView('vendor.pagination.corearsitek');
        Paginator::defaultSimpleView('vendor.pagination.corearsitek');
    }
}
