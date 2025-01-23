<?php
namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        if (! $this->app->environment('local')) {
            Route::middleware(['auth', 'verified'])->group(function () {
                Route::view('/app', 'app')->name('app');
            });
        }
    }
}
