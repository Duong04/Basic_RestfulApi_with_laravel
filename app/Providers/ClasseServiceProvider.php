<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ClasseService;


class ClasseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('classeService', function() {
            return new ClasseService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
