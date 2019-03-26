<?php

namespace App\Providers;

use App\Blueprint\GlobalBlueprint;
use Illuminate\Support\ServiceProvider;

class GlobalBlueprintServiceProvider extends ServiceProvider
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
        $this->app->bind('globalBlueprint', function () {
            return new GlobalBlueprint();
        });
    }
}
