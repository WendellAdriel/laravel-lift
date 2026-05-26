# Hidden

The `Hidden` attribute allows you to hide your model's **public properties** the same way as you would using the `hidden` property on your model, but you can set it directly on your **public properties**.

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
