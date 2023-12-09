# LaravelBoilerPlate by Billy Joel Ranario

## Description

`LaravelBoilerPlate` is a Laravel package that provides a set of tools to help you build APIs and web applications faster.

## Requirements

- **PHP version:** 7.4 and above
- **Laravel version:** 6.0 and above 
- **Laravel Passport:** ^12.0
- **Billyranario Prostarter-Kit:** ^1.0

## Table of Contents

- [Installation](#installation)
- [Contributing](#contributing)
- [License](#license)

## Installation

```bash
composer require laravel/passport
composer require billyranario/prostarterkit

php artisan vendor:publish --tag=prostarter-kit

composer require billyranario/laravel-passport-boilerplate
php artisan vendor:publish --tag=billyranario-boilerplate --force

php artisan migrate
php artisan passport:keys
php artisan passport:client --password
> psk-password-grant
> [0] users

php artisan passport:client --personal
> psk-personal-access
```



edit `config/auth.php` file
```php 
return [
    ..., // Other config
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Add this following code below
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],
];
```

### Creating Superadmin
On your `.env` file, add the following code below. This will provide a default web url for your reset password form page.
The `APP_ADMIN_PASS` is the default password for creating an admin user. You can change it to your desired password. 
If not specified, the default password is `Abc@123456`.
```env
APP_WEB_URL=http://localhost:4202
APP_ADMIN_PASS=secret
```

Once everything is setup, you run the artisan command below to create an admin user.
```bash
php artisan admin:create
```
This will ask you a password to proceed. The password should match to the value you set in the APP_ADMIN_PASS in your `.env` file.


### Admin:API Middleware
```php
    protected $middlewareAliases = [
        // other aliases....
        'admin.api' => \App\Http\Middleware\Admin\AdminApi::class, // <- Insert this line
    ];
```


### Setup UserObserver

`EventServiceProvider.php` Insert the following lines of codes below
```php
use App\Models\User;
use App\Observers\UserObserver;


// INSIDE THE CLASS, ADD THIS
    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        User::class => [UserObserver::class],
    ];
```

### Update Routes

`RouteServiceProvider.php` Insert the followwing
```php

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            
            // INSERT THIS BLOCK
            Route::middleware('api')
                ->prefix('api/admin')
                ->group(base_path('routes/admin.php'));
            // END

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
```



## Contributing

Contributions are welcome. Please submit a PR or open an issue.

## License

This package is open-source and licensed under the MIT License.
