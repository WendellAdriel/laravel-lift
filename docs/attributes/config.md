# Config

The `Config` attribute allows you to set your model's **public properties** configurations for the attributes: `Cast`, `Column`, `Fillable`, `Hidden`, `Immutable`, `Rules` and `Watch`.

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
