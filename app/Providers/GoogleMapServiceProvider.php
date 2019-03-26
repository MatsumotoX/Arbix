<?php

namespace App\Providers;

use App\Google\GoogleMap;
use Illuminate\Support\ServiceProvider;

class GoogleMapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('googlemap', function () {
            return new GoogleMap();
        });
    }
}
