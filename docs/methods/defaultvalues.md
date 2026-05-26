# defaultValues

The `defaultValues` method returns an array with all the **public properties** that have a default value set.

If the default value is a function, the function name will be returned instead of the function result since this is a static call.

```php
$productDefaultValues = Product::defaultValues();

// WILL RETURN
[
    'price' => 0.0,
    'promotional_price' => 'generatePromotionalPrice',
]
```
