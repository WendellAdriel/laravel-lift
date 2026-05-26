# immutableProperties

The `immutableProperties` method returns an array with all the **public properties** that are immutable.

```php
$productImmutableProperties = Product::immutableProperties();

// WILL RETURN
[
    'name',
]
```
