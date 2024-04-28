<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserService;
class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('UserService', function ($app) {
            return new UserService();
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
