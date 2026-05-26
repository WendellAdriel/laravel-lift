# Fillable

When you use the `Lift` trait, your model's **public properties** are automatically set as **guarded**. You can use the `Fillable` attribute to set your **public properties** as **fillable**.

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
