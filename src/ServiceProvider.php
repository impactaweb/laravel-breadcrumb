<?php

namespace Impactaweb\Breadcrumb;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Default configs
        // It can be replaced by the user in laravel /config/breadcrumb.php file
        $this->mergeConfigFrom(__DIR__.'/config/breadcrumb.php', 'breadcrumb');

        // Breadcrumb
        $this->loadViewsFrom(__DIR__.'/resources/views', 'breadcrumb');
    }
}
