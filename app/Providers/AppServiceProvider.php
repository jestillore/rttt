<?php

namespace App\Providers;

use App\Models\Meeting;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Route::bind('meeting', function ($value) {
            return Meeting::where('code', $value)
                ->firstOrFail();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
