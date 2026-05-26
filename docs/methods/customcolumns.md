# customColumns

The `customColumns` method returns an array with all the **public properties** that have a custom column name set.

```php
$productCustomColumns = Product::customColumns();

// WILL RETURN
[
    'product_name' => 'name',
]
```
