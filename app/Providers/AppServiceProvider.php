<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

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
        Relation::morphMap([
            'user' => 'App\Models\User',
            'post' => 'App\Models\Post',
        ]);

        RateLimiter::for('post', function (Request $request) {
            return $request->user() ? 
                Limit::perminute(20)->by($request->ip()) :
                Limit::perminute(10)->by($request->ip());
        });
    }
}
