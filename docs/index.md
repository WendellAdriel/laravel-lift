<div align="center">
    <img src="/laravel-lift-banner.png" alt="Lift for Laravel" style="width: 520px; max-width: 100%; height: auto; margin-bottom: 2rem;">
</div>

# Lift for Laravel

- [Introduction](#introduction)
- [Why use Lift](#why-use-lift)
- [How Lift works](#how-lift-works)
- [Documentation](#documentation)

## Introduction

Lift boosts Eloquent models by letting you describe common model behavior with PHP attributes and typed public properties.

It keeps the model readable in your editor while still relying on Eloquent for persistence, events, casts, validation, and relationships.

## Why use Lift

Eloquent models can collect a lot of configuration across properties, methods, casts, validation rules, and relationship definitions. Lift keeps those decisions close to the property or behavior they describe.

That makes models easier to scan and easier to maintain, especially when a model has custom columns, validation rules, watched properties, immutable fields, or relationship metadata.

## How Lift works

Add the `Lift` trait to an Eloquent model and start using Lift attributes:

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Fillable]
    #[Rules(['required', 'string', 'max:255'])]
    public string $name;
}
```

Lift reads the model metadata and applies it through Eloquent events, so it fits into existing Laravel applications without replacing Eloquent.

## Documentation

Read the docs in this order if you are adding Lift to an application for the first time:

- [Installation](getting-started/installation.md)
- [Cast attribute](attributes/cast.md)
- [Fillable attribute](attributes/fillable.md)
- [Validation attributes](attributes/validation.md)
- [Relationships](attributes/relationships.md)
- [lift:migration command](commands/lift-migration.md)
