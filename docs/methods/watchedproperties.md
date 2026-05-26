# watchedProperties

The `watchedProperties` method returns an array with all the **public properties** that have a custom event set.

```php
$productWatchedProperties = Product::watchedProperties();

// WILL RETURN
[
    'price' => PriceChangedEvent::class,
]
```
