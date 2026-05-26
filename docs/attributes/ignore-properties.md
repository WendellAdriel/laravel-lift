# Ignore Properties

By default, when using the `Lift` trait, the model will ignore some public properties, that are "internal" properties,
by being handled by `Lift`.

If you want to add additional properties to be ignored, you can use the `IgnoreProperties` attribute:

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\IgnoreProperties;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

#[IgnoreProperties('hash', 'hash2')]
final class Product extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $custom_id;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    public string $name;

    public string $hash;

    public string $hash2;
}
```
