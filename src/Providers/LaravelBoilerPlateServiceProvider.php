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
            __DIR__ . '/../App/Console/Commands'                         => app_path('Console/Commands'),
            __DIR__ . '/../App/Constants'                                => app_path('Constants'),
            __DIR__ . '/../App/Dtos'                                     => app_path('Dtos'),
            __DIR__ . '/../App/Http/Controllers/Admin'                   => app_path('Http/Controllers/Admin'),
            __DIR__ . '/../App/Http/Controllers/User'                    => app_path('Http/Controllers/User'),
            __DIR__ . '/../App/Http/Controllers/AuthController.php'      => app_path('Http/Controllers/AuthController.php'),
            __DIR__ . '/../App/Http/Middleware/Admin'                    => app_path('Http/Middleware/Admin'),
            __DIR__ . '/../App/Http/Requests/auth'                       => app_path('Http/Requests/auth'),
            __DIR__ . '/../App/Http/Requests/user'                       => app_path('Http/Requests/user'),
            __DIR__ . '/../App/Http/Requests/admin'                      => app_path('Http/Requests/admin'),
            __DIR__ . '/../App/Http/Resources'                           => app_path('Http/Resources'),
            __DIR__ . '/../App/Jobs/SendResetPasswordLink.php'           => app_path('Jobs/SendResetPasswordLink.php'),
            __DIR__ . '/../App/Models/User.php'                          => app_path('Models/User.php'),
            __DIR__ . '/../App/Models/ActivityLog.php'                   => app_path('Models/ActivityLog.php'),
            __DIR__ . '/../App/Notifications'                            => app_path('Notifications'),
            __DIR__ . '/../App/Observers'                                => app_path('Observers'),
            __DIR__ . '/../App/Repositories'                             => app_path('Repositories'),
            __DIR__ . '/../App/Services'                                 => app_path('Services'),
            __DIR__ . '/../App/Traits'                                   => app_path('Traits'),
            __DIR__ . '/../Database/Migrations'                          => database_path('migrations'),
            __DIR__ . '/../Routes/api.php'                               => base_path('routes/api.php'),
            __DIR__ . '/../Routes/admin.php'                             => base_path('routes/admin.php'),
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
