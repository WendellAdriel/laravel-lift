# Primary Key

By default, the Eloquent Model uses the `id` column as the primary key as an auto-incrementing integer value. With the `PrimaryKey` attribute you can configure in a simple and easy way the primary key of your model.

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

If your model uses a column with a different type and not incrementing like a UUID, you can set it using the `PrimaryKey` attribute like this:

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
