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
php artisan vendor:publish --tag=billyranario-boilerplate

php artisan migrate

php artisan passport:client --password
> psk-password-grant
> [0] users

php artisan passport:client --personal
> psk-personal-access
```

## Contributing

Contributions are welcome. Please submit a PR or open an issue.

## License

This package is open-source and licensed under the MIT License.
