<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CourseService;
class CourseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('courseService', function($app) {
            return new CourseService();
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
