# Cast

The `Cast` attribute allows you to cast your model's **public properties** to a specific type and also to type your public properties. It works the same way as it would be using the `casts` property on your model, but you can set it directly on your **public properties**.

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
    
    #[Cast('array')]
    public ?array $json_column;
}
```

For the casting to work properly with your public properties, you need to use these helper methods that are available in
your **Lift** model:

## castAndCreate

A replacement for the `create` method. It will cast your public properties and create a new model instance.

```php
$product = Product::castAndCreate([
    'name' => 'Product 1',
    'price' => '10.99',
    'category_id' => '1',
    'is_active' => 1,
    'promotion_expires_at' => '2023-12-31 23:59:59',
    'json_column' => ['foo' => 'bar'],
]);
```

## castAndFill

A replacement for the `fill` method. It will cast your public properties and fill the model instance.

```php
$product = new Product();
$product->castAndFill([
    'name' => 'Product 1',
    'price' => '10.99',
    'category_id' => '1',
    'is_active' => 1,
    'promotion_expires_at' => '2023-12-31 23:59:59',
    'json_column' => '{"foo":"bar"}', // You can also pass a JSON string
]);
$product->save();
```

## castAndSet

This can be used to cast and set a single public property.

```php
$product = new Product();
$product->castAndSet('is_active', 1);
```

## castAndUpdate

A replacement for the `update` method. It will cast your public properties and update the model instance.

```php
$product = Product::query()->find(1);
$product->castAndUpdate([
    'name' => 'Product 1',
    'price' => '10.99',
    'category_id' => '1',
    'is_active' => 1,
    'promotion_expires_at' => '2023-12-31 23:59:59',
    'json_column' => ['foo' => 'bar'],
]);
```
