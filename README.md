<div align="center">
    <img src="https://github.com/WendellAdriel/laravel-lift/raw/main/art/laravel-lift-banner.png" alt="Lift for Laravel" height="400"/>
    <p>
        <h1>🏋️ Lift for Laravel</h1>
        Take your Eloquent Models to the next level
    </p>
</div>

<p align="center">
    <a href="https://packagist.org/packages/WendellAdriel/laravel-lift"><img src="https://img.shields.io/packagist/v/WendellAdriel/laravel-lift.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/WendellAdriel/laravel-lift"><img src="https://img.shields.io/packagist/php-v/WendellAdriel/laravel-lift.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/WendellAdriel/laravel-lift"><img src="https://img.shields.io/badge/Laravel-10.x,%2011.x-brightgreen.svg?style=flat-square" alt="Laravel Version"></a>
    <a href="https://github.com/WendellAdriel/laravel-lift/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/WendellAdriel/laravel-lift/tests.yml?branch=main&label=Tests"> </a>
</p>

**Lift** is a package that boosts your Eloquent Models in Laravel.

It lets you create public properties in Eloquent Models that match your table schema. This makes your models easier to
read and work with in any IDE.

The package intelligently uses PHP 8’s attributes, and gives you complete freedom in setting up your models. For
instance, you can put validation rules right into your models - a simple and easy-to-understand arrangement compared
to a separate request class. Plus, all these settings are easily reachable through handy new methods.

With a focus on simplicity, **Lift** depends on **Eloquent Events** to work. This means the package fits easily into your
project, without needing any major changes (unless you’ve turned off event triggering).

## Documentation
[![Docs Button]][Docs Link] [![DocsRepo Button]][DocsRepo Link]

## Installation

```bash
composer require wendelladriel/laravel-lift
```

## Credits

- [Wendell Adriel](https://github.com/WendellAdriel)
- [All Contributors](../../contributors)

## Contributing

Check the **[Contributing Guide](CONTRIBUTING.md)**.

<!---------------------------------------------------------------------------->
[Docs Button]: https://img.shields.io/badge/Website-B30E2E?style=for-the-badge&logoColor=white&logo=GitBook
[Docs Link]: https://wendell-adriel.gitbook.io/laravel-lift/
[DocsRepo Button]: https://img.shields.io/badge/Repository-3884FF?style=for-the-badge&logoColor=white&logo=GitBook
[DocsRepo Link]: https://github.com/WendellAdriel/laravel-lift-docs
