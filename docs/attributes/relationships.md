# Relationships

With **Lift**, you can configure all of your Model **relationships** using **Attributes**. It works the same way when defining them with methods, so all of them accept the same parameters as the methods.

## BelongsTo

```php
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;

#[BelongsTo(User::class)]
final class Post extends Model
{
    use Lift;
    // ...
}
```

## BelongsToMany

<pre class="language-php"><code class="lang-php">use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;
<strong>
</strong><strong>#[BelongsToMany(Role::class)]
</strong>final class User extends Model
{
    use Lift;
    // ...
}
</code></pre>

```php
use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;

#[BelongsToMany(User::class)]
final class Role extends Model
{
    use Lift;
    // ...
}
```

## HasMany

<pre class="language-php"><code class="lang-php">use WendellAdriel\Lift\Attributes\Relations\HasMany;
<strong>
</strong><strong>#[HasMany(Post::class)]
</strong>final class User extends Model
{
    use Lift;
    // ...
}
</code></pre>

## HasManyThrough

```php
use WendellAdriel\Lift\Attributes\Relations\HasMany;
use WendellAdriel\Lift\Attributes\Relations\HasManyThrough;

#[HasMany(User::class)]
#[HasManyThrough(Post::class, User::class)]
final class Country extends Model
{
    use Lift;
    // ...
}
```

```php
use WendellAdriel\Lift\Attributes\Relations\HasMany;

#[HasMany(Post::class)]
final class User extends Model
{
    use Lift;
    // ...
}
```

```php
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;

#[BelongsTo(User::class)]
final class Post extends Model
{
    use Lift;
    // ...
}
```

## HasOne

```php
use WendellAdriel\Lift\Attributes\Relations\HasOne;

#[HasOne(Phone::class)]
final class User extends Model
{
    use Lift;
    // ...
}
```

## HasOneThrough

```php
use WendellAdriel\Lift\Attributes\Relations\HasOne;
use WendellAdriel\Lift\Attributes\Relations\HasOneThrough;

#[HasOneThrough(Manufacturer::class, Computer::class)]
#[HasOne(Computer::class)]
final class Seller extends Model
{
    use Lift;
    // ...
}
```

```php
use WendellAdriel\Lift\Attributes\Relations\HasOne;

#[HasOne(Manufacturer::class)]
final class Computer extends Model
{
    use Lift;
    // ...
}
```

## MorphMany/MorphTo

```php
use WendellAdriel\Lift\Attributes\Relations\MorphMany;

#[MorphMany(Image::class, 'imageable')]
final class Post extends Model
{
    use Lift;
    // ...
}
```

```php
use WendellAdriel\Lift\Attributes\Relations\MorphTo;

#[MorphTo('imageable')]
final class Image extends Model
{
    use Lift;
    // ...
}
```

## MorphOne/MorphTo

```php
use WendellAdriel\Lift\Attributes\Relations\MorphOne;

#[MorphOne(Image::class, 'imageable')]
final class User extends Model
{
    use Lift;
    // ...
}
```

```php
use WendellAdriel\Lift\Attributes\Relations\MorphTo;

#[MorphTo('imageable')]
final class Image extends Model
{
    use Lift;
    // ...
}
```

## MorphToMany/MorphedByMany

```php
use WendellAdriel\Lift\Attributes\Relations\MorphToMany;

#[MorphToMany(Tag::class, 'taggable')]
final class Post extends Model
{
    use Lift;
    // ...
}
```

```php
use WendellAdriel\Lift\Attributes\Relations\MorphedByMany;

#[MorphedByMany(Post::class, 'taggable')]
final class Tag extends Model
{
    use Lift;
    // ...
}
```

## Customizing the Relationship

All the attributes listed above, except the `MorphTo` attribute, accept an additional parameter to customize the relationship name.

```php
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;

#[BelongsTo(User::class, 'author')]
final class Post extends Model
{
    use Lift;
    // ...
}

$post->author; // Will return the User model
```

After the `name` parameter, you can pass the same parameters as you would do when defining the relationship using methods, for example, to customize the foreign key.

```php
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;

#[BelongsTo(User::class, 'author', 'custom_id', 'id')]
final class Post extends Model
{
    use Lift;
    // ...
}
```

For the `BelongsToMany` relationship you can also customize the pivot with the `pivotModel` and `pivotColumns` parameters:

```php
use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;

#[BelongsToMany(User::class, pivotModel: OrganizationUser::class, pivotColumns: ['is_owner'])]
class Organization extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    public string $name;
}
```
