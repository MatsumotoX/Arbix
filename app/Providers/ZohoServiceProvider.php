<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Zoho\ZohoAPI;

class ZohoServiceProvider extends ServiceProvider
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
        $this->app->bind('zoho', function () {
            return new ZohoAPI();
        });
    }
}
