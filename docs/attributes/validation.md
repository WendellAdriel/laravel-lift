# Validation

**Lift** provides three attributes to help you validate your model's **public properties**.

## Rules

> ⚠️ **The rules will be validated only when you save your model (create or update)**

The `Rules` attribute allows you to set your model's **public properties** validation rules the same way as you would do with the `rules` function on a `FormRequest`, but you can set it directly on your **public properties**.

```php
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Hidden;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Rules(['required', 'string'])]
    #[Fillable]
    public string $name;

    #[Rules(['required', 'numeric'])]
    #[Fillable]
    #[Cast('float')]
    public float $price;

    #[Rules(['required', 'integer'])]
    #[Fillable]
    #[Cast('int')]
    public int $category_id;

    #[Rules(['required', 'boolean'])]
    #[Fillable]
    #[Cast('boolean')]
    public bool $is_active;

    #[Rules(['required', 'date_format:Y-m-d H:i:s'])]
    #[Fillable]
    #[Cast('immutable_datetime')]
    public CarbonImmutable $promotion_expires_at;

    #[Rules(['required', 'string'])]
    #[Hidden]
    #[Fillable]
    public string $sensitive_data;
}
```

You can also pass a second parameter to the `Rules` attribute to set a custom error message for the validation rule.

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

final class Product extends Model
{
    use Lift;

    #[Rules(['required', 'string'], ['required' => 'The Product name can not be empty'])]
    #[Fillable]
    public string $name;
}
```

The validation messages work with localization, so you can set your messages ina `lang` file and under the hood,
**Lift** will use the `__()` helper to get the message.

## CreateRules

> ⚠️ **The rules will be validated only when you create your model**

The `CreateRules` attribute works the same way as the `Rules` attribute, but the rules will be validated only when you create your model.

In the example below the `name` property will be validated with the set rules for both when creating and updating the model. The `email` and `password` properties will be validated only when creating the model.

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\CreateRules;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

class User extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    #[Rules(rules: ['required', 'string'], messages: ['required' => 'The user name cannot be empty'])]
    public string $name;

    #[Fillable]
    #[CreateRules(rules: ['required', 'email'], messages: ['required' => 'The user email cannot be empty'])]
    public string $email;

    #[Fillable]
    #[CreateRules(['required', 'string', 'min:8'])]
    public string $password;
}
```

## UpdateRules

> ⚠️ **The rules will be validated only when you update your model**

The `UpdateRules` attribute works the same way as the `Rules` attribute, but the rules will be validated only when you update your model.

In the example below the `name` property will be validated with the set rules for both when creating and updating the model. The `email` and `password` properties will be validated only when updating the model.

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Attributes\UpdateRules;
use WendellAdriel\Lift\Lift;

class User extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    #[Rules(rules: ['required', 'string'], messages: ['required' => 'The user name cannot be empty'])]
    public string $name;

    #[Fillable]
    #[UpdateRules(rules: ['required', 'email'], messages: ['required' => 'The user email cannot be empty'])]
    public string $email;

    #[Fillable]
    #[UpdateRules(['required', 'string', 'min:8'])]
    public string $password;
}
```

## Mixing Rules

You can also mix the three validation attributes to set different rules for creating and updating your model.

```php
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\CreateRules;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Attributes\UpdateRules;
use WendellAdriel\Lift\Lift;

class User extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    #[Rules(rules: ['required', 'string'], messages: ['required' => 'The user name cannot be empty'])]
    public string $name;

    #[Fillable]
    #[CreateRules(rules: ['required', 'email'], messages: ['required' => 'The user email cannot be empty'])]
    #[UpdateRules(['sometimes', 'email'])]
    public string $email;

    #[Fillable]
    #[CreateRules(['required', 'string', 'min:8'])]
    #[UpdateRules(rules: ['sometimes', 'string', 'min:8'], messages: ['min' => 'The password must be at least 8 characters long'])]
    public string $password;
}
```
