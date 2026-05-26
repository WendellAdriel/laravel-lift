# Watch

By default, **Eloquent** already fires events when updating models, but it is a generic event. With the `Watch` attribute you can set a specific event to be fired when a specific **public property** is updated. The event will receive as a parameter the updated model instance.

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
