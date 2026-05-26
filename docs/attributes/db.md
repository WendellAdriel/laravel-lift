# DB

The `DB` class attribute allows you to customize the database connection, table and timestamps of your model. If you don't set any of the attribute parameters, the default values will be used.

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
