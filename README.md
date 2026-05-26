> [!WARNING]
> **This is an experimental package!**

<div align="center">
    <img src="https://github.com/wendelladriel/laravel-lift/raw/main/art/laravel-lift-banner.png" alt="Lift for Laravel" height="400"/>
    <p>
        <h1>🏋️ Lift for Laravel</h1>
        Take your Eloquent Models to the next level
    </p>
</div>

<p align="center">
    <a href="https://packagist.org/packages/wendelladriel/laravel-lift"><img src="https://img.shields.io/packagist/v/wendelladriel/laravel-lift.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/wendelladriel/laravel-lift"><img src="https://img.shields.io/packagist/php-v/wendelladriel/laravel-lift.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/wendelladriel/laravel-lift"><img src="https://badge.laravel.cloud/badge/wendelladriel/laravel-lift?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/wendelladriel/laravel-lift/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/wendelladriel/laravel-lift/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/wendelladriel/laravel-lift"><img src="https://img.shields.io/packagist/dt/wendelladriel/laravel-lift.svg?style=flat-square" alt="Total Downloads"></a>
</p>

## Installation

You can install the package via composer:

```bash
composer require wendelladriel/laravel-lift
```

## Usage

Add the `Lift` trait to models that should use typed public properties and Lift attributes:

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Fillable]
    #[Rules(['required', 'string', 'max:255'])]
    public string $name;

    #[Fillable]
    #[Cast('integer')]
    #[Rules(['required', 'integer', 'min:0'])]
    public int $stock;
}
```

Lift reads those attributes and applies the matching Eloquent configuration through model events. This keeps model behavior close to the property it describes while still using standard Eloquent models.

Access the full documentation [here](https://laravel-lift.wendelladriel.com).

## Changelog

Please see the [changelog](https://laravel-lift.wendelladriel.com/getting-started/changelog) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Lift! You can read the contribution guide [here](CONTRIBUTING.md).

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Wendell Adriel](https://github.com/WendellAdriel)
- [All Contributors](../../contributors)

## License

Lift is open-sourced software licensed under the [MIT license](LICENSE).
