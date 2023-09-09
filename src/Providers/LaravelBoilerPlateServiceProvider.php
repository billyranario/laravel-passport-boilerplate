<?php

namespace Billyranario\LaravelBoilerPlate\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelBoilerPlateServiceProvider extends ServiceProvider
{
    /**
     * @var string $tag
     */
    protected string $tag = 'billyranario-boilerplate';

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../App/Constants'                                => app_path('Constants'),
            __DIR__.'/../App/Dtos'                                     => app_path('Dtos'),
            __DIR__.'/../App/Http/Controllers/AuthController.php'      => app_path('Http/Controllers/AuthController.php'),
            __DIR__.'/../App/Http/Requests/auth'                       => app_path('Http/Requests/auth'),
            __DIR__.'/../App/Http/Resources'                           => app_path('Http/Resources'),
            __DIR__.'/../App/Http/Resources'                           => app_path('Http/Resources'),
            __DIR__.'/../App/Jobs/SendResetPasswordLink.php'           => app_path('Jobs/SendResetPasswordLink.php'),
            __DIR__.'/../App/Models/User.php'                          => app_path('Models/User.php'),
            __DIR__.'/../App/Notifications'                             => app_path('Notifications'),
            __DIR__.'/../App/Repositories'                             => app_path('Repositories'),
            __DIR__.'/../App/Services'                                 => app_path('Services'),
            __DIR__.'/../App/Traits'                                   => app_path('Traits'),
        ], $this->tag);
        
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register(): void
    {
        // You can register any bindings or singleton instances here.
        // For example:
        // $this->app->singleton(SomeService::class, function ($app) {
        //    return new SomeService();
        // });
    }
}
