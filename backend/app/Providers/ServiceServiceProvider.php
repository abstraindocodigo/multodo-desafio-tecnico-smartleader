<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthService;
use App\Services\TaskService;
use App\Services\ExportService;
use App\Services\JwtService;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(JwtService::class, function ($app) {
            return new JwtService();
        });

        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService($app->make(JwtService::class));
        });

        $this->app->singleton(TaskService::class, function ($app) {
            return new TaskService();
        });

        $this->app->singleton(ExportService::class, function ($app) {
            return new ExportService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
