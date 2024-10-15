<?php

namespace App\Providers;

use App\Contracts\Translator;
use App\Models\Meeting;
use App\Translators\Nothing;
use App\Translators\OpenAi;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
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

        App::bind(Translator::class, OpenAi::class);
        URL::forceScheme('https');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
