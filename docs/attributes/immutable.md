# Immutable

The `Immutable` attribute allows you to set your model's **public properties** as immutable. This means that once the model is created, the **public properties** will not be able to be changed. If you try to change the value of an immutable property an `WendellAdriel\Lift\Exceptions\ImmutablePropertyException` will be thrown.

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
