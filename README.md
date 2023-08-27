<div align="center">
    <img src="https://github.com/WendellAdriel/laravel-lift/raw/main/art/laravel-lift-logo.svg" alt="Lift for Laravel" height="300"/>
    <p>
        <h1>🏋️ Lift for Laravel</h1>
        Take your Eloquent Models to the next level
    </p>
</div>

<p align="center">
    <a href="https://packagist.org/packages/WendellAdriel/laravel-lift"><img src="https://img.shields.io/packagist/v/WendellAdriel/laravel-lift.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/WendellAdriel/laravel-lift"><img src="https://img.shields.io/packagist/php-v/WendellAdriel/laravel-lift.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/WendellAdriel/laravel-validated-dto"><img src="https://img.shields.io/badge/Laravel-9.x,%2010.x-brightgreen.svg?style=flat-square" alt="Laravel Version"></a>
    <a href="https://github.com/WendellAdriel/laravel-lift/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/WendellAdriel/laravel-lift/tests.yml?branch=main&label=Tests"> </a>
</p>

<p align="center">
    <a href="#documentation">Documentation</a> |
    <a href="#installation">Installation</a> |
    <a href="#trait">Trait</a> |
    <a href="#attributes">Attributes</a> |
    <a href="#method">Methods</a> |
    <a href="#credits">Credits</a> |
    <a href="#contributing">Contributing</a>
</p>

**Lift** is a package that provides a `Trait`, `Attributes` and some `methods` to your **Eloquent Models** to make them more powerful.

> ⚠️
> **Currently, this package relies heavily on Eloquent Events to work properly, so when dealing with code that does not fire**
> **these events, it could have unexpected issues. If you find any issues, create an issue and/or submit a PR for it.**

## Documentation
[![Docs Button]][Docs Link]

## Installation

```bash
composer require wendelladriel/laravel-lift
```

## Trait

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;
}
```

Using the `Lift` trait, your model now supports **public properties** to be set on it, so you can have a more readable code and
a better **DX** in your code editor IDE with **auto-completion**.

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    public $name;

    public $price;

    public $category_id;

    public $is_active;

    public $promotion_expires_at;
}
```

## Attributes

While the `Lift` trait provides a way to set **public properties** on your model, the `Attributes` take your model to the next level.

### Cast

The `Cast` attribute allows you to cast your model's **public properties** to a specific type and also to type your public properties.
It works the same way as it would be using the `casts` property on your model, but you can set it directly on your **public properties**.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    public string $name;

    #[Cast('float')]
    public float $price;

    #[Cast('int')]
    public int $category_id;

    #[Cast('boolean')]
    public bool $is_active;

    #[Cast('immutable_datetime')]
    public CarbonImmutable $promotion_expires_at;
}
```

### Fillable

When you use the `Lift` trait, your model's **public properties** are automatically set as **guarded**. You can use the
`Fillable` attribute to set your **public properties** as **fillable**.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Fillable]
    public string $name;

    #[Fillable]
    #[Cast('float')]
    public float $price;

    #[Fillable]
    #[Cast('int')]
    public int $category_id;

    #[Fillable]
    #[Cast('boolean')]
    public bool $is_active;

    #[Fillable]
    #[Cast('immutable_datetime')]
    public CarbonImmutable $promotion_expires_at;
}
```

### Hidden

The `Hidden` attribute allows you to hide your model's **public properties** the same way as you would do using the
`hidden` property on your model, but you can set it directly on your **public properties**.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Hidden;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Fillable]
    public string $name;

    #[Fillable]
    #[Cast('float')]
    public float $price;

    #[Fillable]
    #[Cast('int')]
    public int $category_id;

    #[Fillable]
    #[Cast('boolean')]
    public bool $is_active;

    #[Fillable]
    #[Cast('immutable_datetime')]
    public CarbonImmutable $promotion_expires_at;

    #[Hidden]
    #[Fillable]
    public string $sensitive_data;
}
```

### Rules

> ⚠️ **The rules will be validated only when you save your model (create or update)**

The `Rules` attribute allows you to set your model's **public properties** validation rules the same way as you would do
with the `rules` function on a `FormRequest`, but you can set it directly on your **public properties**.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Hidden;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Rules(['required', 'string'])]
    #[Fillable]
    public string $name;

    #[Rules(['required', 'numeric'])]
    #[Fillable]
    #[Cast('float')]
    public float $price;

    #[Rules(['required', 'integer'])]
    #[Fillable]
    #[Cast('int')]
    public int $category_id;

    #[Rules(['required', 'boolean'])]
    #[Fillable]
    #[Cast('boolean')]
    public bool $is_active;

    #[Rules(['required', 'date_format:Y-m-d H:i:s'])]
    #[Fillable]
    #[Cast('immutable_datetime')]
    public CarbonImmutable $promotion_expires_at;

    #[Rules(['required', 'string'])]
    #[Hidden]
    #[Fillable]
    public string $sensitive_data;
}
```

You can also pass a second parameter to the `Rules` attribute to set a custom error message for the validation rule.

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    public string $name;
}
```

### Config

The `Config` attribute allows you to set your model's **public properties** configurations for the attributes:
`Cast`, `Column`, `Fillable`, `Hidden` and `Rules`.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Lift;

class Product extends Model
{
    use Lift;

    #[Config(fillable: true, rules: ['required', 'string'], messages: ['required' => 'The PRODUCT NAME field cannot be empty.'])]
    public string $name;

    #[Config(fillable: true, column: 'description', rules: ['required', 'string'])]
    public string $product_description;

    #[Config(fillable: true, cast: 'float', default: 0.0, rules: ['sometimes', 'numeric'])]
    public float $price;

    #[Config(fillable: true, cast: 'int', hidden: true, rules: ['required', 'integer'])]
    public int $random_number;

    #[Config(fillable: true, cast: 'immutable_datetime', rules: ['required', 'date_format:Y-m-d H:i:s'])]
    public CarbonImmutable $expires_at;
}
```

### Primary Key

By default, the Eloquent Model uses the `id` column as the primary key as an auto-incrementing integer value.
With the `PrimaryKey` attribute you can configure in a simple and easy way the primary key of your model.

If your model uses a different column as the primary key, you can set it using the `PrimaryKey` attribute:

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $custom_id;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    public string $name;
}
```

If your model uses a column with a different type and not incrementing like a UUID, you can set it using the
`PrimaryKey` attribute like this:

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[PrimaryKey(type: 'string', incrementing: false)]
    public string $uuid;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    public string $name;
}
```

### DB

The `DB` class attribute allows you to customize the database connection, table and timestamps of your model. If you
don't set any of the attribute parameters, the default values will be used.

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

#[DB(connection: 'mysql', table: 'custom_products_table', timestamps: false)]
final class Product extends Model
{
    use Lift;

    #[PrimaryKey(type: 'string', incrementing: false)]
    public string $uuid;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    public string $name;
}
```

### Column

The `Column` attribute allows you to customize the column name of your model's **public properties**.
In the example below the `product_name` property will be mapped to the `name` column on the database table:

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    #[Column('name')]
    public string $product_name;
}
```

You can also set a default value for your **public properties** using the `Column` attribute.
In the example below the `price` property will be mapped to the `price` column on the database table and will have a
default value of `0.0`:

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    #[Column('name')]
    public string $product_name;
    
    #[Column(default: 0.0)]
    #[Cast('float')]
    public float $price;
}
```

You can also set a default value for your **public properties** passing a function name as the default value:

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    #[Column('name')]
    public string $product_name;
    
    #[Column(default: 0.0)]
    #[Cast('float')]
    public float $price;
    
    #[Column(default: 'generatePromotionalPrice')]
    #[Cast('float')]
    public float $promotional_price;
    
    public function generatePromotionalPrice(): float
    {
        return $this->price * 0.8;
    }
}
```

## Methods

When using the `Lift` trait, your model will have some new methods available.

### customColumns

The `customColumns` method returns an array with all the **public properties** that have a custom column name set.

```php
$productCustomColumns = Product::customColumns();

// WILL RETURN
[
    'product_name' => 'name',
]
```

### defaultValues

The `defaultValues` method returns an array with all the **public properties** that have a default value set.

If the default value is a function, the function name will be returned instead of the function result since this is
a static call.

```php
$productDefaultValues = Product::defaultValues();

// WILL RETURN
[
    'price' => 0.0,
    'promotional_price' => 'generatePromotionalPrice',
]
```

### validationRules

The `validationRules` method returns an array with all the validation rules for your model's **public properties**.

```php
$productRules = Product::validationRules();

// WILL RETURN
[
    'name' => ['required', 'string'],
    'price' => ['required', 'numeric'],
    'random_number' => ['required', 'integer'],
    'expires_at' => ['required', 'date_format:Y-m-d H:i:s'],
]
```

### validationMessages

The `validationMessages` method returns an array with all the validation messages for your model's **public properties**.

```php
$productRules = Product::validationMessages();

// WILL RETURN
[
    'name' => [
        'required' => 'The PRODUCT NAME field cannot be empty.',
    ],
    'price' => [],
    'random_number' => [],
    'expires_at' => [],
]
```

## Credits

- [Wendell Adriel](https://github.com/WendellAdriel)
- [All Contributors](../../contributors)

## Contributing

Check the **[Contributing Guide](CONTRIBUTING.md)**.

<!---------------------------------------------------------------------------->
[Docs Button]: https://img.shields.io/badge/Documentation-40CA00?style=for-the-badge&logoColor=white&logo=GitBook
[Docs Link]: https://wendell-adriel.gitbook.io/laravel-lift/
