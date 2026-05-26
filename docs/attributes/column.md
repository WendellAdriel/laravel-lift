# Column

The `Column` attribute allows you to customize the column name of your model's **public properties**. In the example below the `product_name` property will be mapped to the `name` column on the database table:

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

You can also set a default value for your **public properties** using the `Column` attribute. In the example below the `price` property will be mapped to the `price` column on the database table and will have a default value of `0.0`:

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

You can also set a default value for your **public properties** by passing a function name as the default value:

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
