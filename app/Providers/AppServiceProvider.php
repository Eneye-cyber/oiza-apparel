<?php

namespace App\Providers;

use App\Models\Category;
use App\Observers\CategoryObserver;
use App\Services\CategoryService;
use App\View\Components\CartDrawer;
use App\View\Components\Header;
use App\View\Components\Submenu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        // Register the CategoryService so it can be injected into other classes.
        // This ensures that the same instance is used throughout the application.
        // This is useful for services that do not maintain state or configuration.
        // It allows for easy dependency injection and testing.

        // $this->app->bind(CategoryService::class, function ($app) {
        //     return new CategoryService();
        // });
        // Alternatively, you can use singleton if you want to ensure only one instance is created.
        // This is useful for services that manage state or configuration.
        // Good for services that should be shared across the application
        $this->app->singleton(CategoryService::class, function ($app) {
            return new CategoryService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        Category::observe(CategoryObserver::class);
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            Blade::component('header', Header::class);
            Blade::component('submenu', Submenu::class);
            Blade::component('cart-drawer', CartDrawer::class);
        }

    }
}
