<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CountryService;

class CountryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('countryService', function ($app) {
            return new CountryService();
        });
    }
}