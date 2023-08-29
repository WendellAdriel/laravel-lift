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
    <a href="#attributes">Attributes</a> |
    <a href="#method">Methods</a> |
    <a href="#credits">Credits</a> |
    <a href="#contributing">Contributing</a>
</p>

**Lift** is a package that boosts your Eloquent Models in Laravel.

It lets you create public properties in Eloquent Models that match your table schema. This makes your models easier to
read and work with in any IDE.

The package intelligently uses PHP 8’s attributes, and gives you complete freedom in setting up your models. For
instance, you can put validation rules right into your models - a simple and easy-to-understand arrangement compared
to a separate request class. Plus, all these settings are easily reachable through handy new methods.

With a focus on simplicity, **Lift** depends on **Eloquent Events** to work. This means the package fits easily into your
project, without needing any major changes (unless you’ve turned off event triggering).

To start using **Lift**, you just need to add the `Lift` trait to your Eloquent Models, and you're ready to go.

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;
}
```

## Documentation
[![Docs Button]][Docs Link]

## Installation

```bash
composer require wendelladriel/laravel-lift
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
`Cast`, `Column`, `Fillable`, `Hidden`, `Immutable`, `Rules` and `Watch`.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Config(fillable: true, rules: ['required', 'string'], messages: ['required' => 'The PRODUCT NAME field cannot be empty.'])]
    public string $name;

    #[Config(fillable: true, column: 'description', rules: ['required', 'string'])]
    public string $product_description;

    #[Config(fillable: true, cast: 'float', default: 0.0, rules: ['sometimes', 'numeric'], watch: ProductPriceChanged::class)]
    public float $price;

    #[Config(fillable: true, cast: 'int', hidden: true, rules: ['required', 'integer'])]
    public int $random_number;

    #[Config(fillable: true, cast: 'immutable_datetime', immutable: true , rules: ['required', 'date_format:Y-m-d H:i:s'])]
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

### Immutable

The `Immutable` attribute allows you to set your model's **public properties** as immutable. This means that once the model
is created, the **public properties** will not be able to be changed. If you try to change the value of an immutable property
an `WendellAdriel\Lift\Exceptions\ImmutablePropertyException` will be thrown.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Immutable;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Immutable]
    #[Fillable]
    public string $name;

    #[Fillable]
    #[Cast('float')]
    public float $price;
}
```

Example:

```php
$product = Product::create([
    'name' => 'Product Name',
    'price' => 10.0,
]);

$product->name = 'New Product Name';
$product->save(); // Will throw an ImmutablePropertyException
```

### Watch

By default, **Eloquent** already fires events when updating models, but it is a generic event. With the `Watch` attribute
you can set a specific event to be fired when a specific **public property** is updated. The event will receive as a parameter
the updated model instance.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Tests\Datasets\PriceChangedEvent;
use Tests\Datasets\RandomNumberChangedEvent;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Watch;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Fillable]
    public string $name;

    #[Watch(PriceChangedEvent::class)]
    #[Fillable]
    #[Cast('float')]
    public float $price;

    #[Fillable]
    #[Cast('int')]
    public int $random_number;

    #[Fillable]
    #[Cast('immutable_datetime')]
    public CarbonImmutable $expires_at;
}
```

```php
final class PriceChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Product $product,
    ) {
    }
}
```

### Relationships

With **Lift**, you can configure all of your Model **relationships** using **Attributes**. It works the same way when defining
them with methods, so all of them accept the same parameters as the methods.

#### BelongsTo

```php
#[BelongsTo(User::class)]
final class Post extends Model
{
    use Lift;
    // ...
}
```

#### BelongsToMany

```php
#[BelongsToMany(Role::class)]
final class User extends Model
{
    use Lift;
    // ...
}
```

```php
#[BelongsToMany(User::class)]
final class Role extends Model
{
    use Lift;
    // ...
}
```

#### HasMany

```php
#[HasMany(Post::class)]
final class User extends Model
{
    use Lift;
    // ...
}
```

#### HasManyThrough

```php
#[HasMany(User::class)]
#[HasManyThrough(Post::class, User::class)]
final class Country extends Model
{
    use Lift;
    // ...
}
```

```php
#[HasMany(Post::class)]
final class User extends Model
{
    use Lift;
    // ...
}
```

```php
#[BelongsTo(User::class)]
final class Post extends Model
{
    use Lift;
    // ...
}
```

#### HasOne

```php
#[HasOne(Phone::class)]
final class User extends Model
{
    use Lift;
    // ...
}
```

#### HasOneThrough

```php
#[HasOneThrough(Manufacturer::class, Computer::class)]
#[HasOne(Computer::class)]
final class Seller extends Model
{
    use Lift;
    // ...
}
```

```php
#[HasOne(Manufacturer::class)]
final class Computer extends Model
{
    use Lift;
    // ...
}
```

#### MorphMany/MorphTo

```php
#[MorphMany(Image::class, 'imageable')]
final class Post extends Model
{
    use Lift;
    // ...
}
```

```php
#[MorphTo('imageable')]
final class Image extends Model
{
    use Lift;
    // ...
}
```

#### MorphOne/MorphTo

```php
#[MorphOne(Image::class, 'imageable')]
final class User extends Model
{
    use Lift;
    // ...
}
```

```php
#[MorphTo('imageable')]
final class Image extends Model
{
    use Lift;
    // ...
}
```

#### MorphToMany/MorphedByMany

```php
#[MorphToMany(Tag::class, 'taggable')]
final class Post extends Model
{
    use Lift;
    // ...
}
```

```php
#[MorphedByMany(Post::class, 'taggable')]
final class Tag extends Model
{
    use Lift;
    // ...
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

### immutableProperties

The `immutableProperties` method returns an array with all the **public properties** that are immutable.

```php
$productImmutableProperties = Product::immutableProperties();

// WILL RETURN
[
    'name',
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

### watchedProperties

The `watchedProperties` method returns an array with all the **public properties** that have a custom event set.

```php
$productWatchedProperties = Product::watchedProperties();

// WILL RETURN
[
    'price' => PriceChangedEvent::class,
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
