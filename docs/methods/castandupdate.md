# castAndUpdate

A replacement for the `update` method. It will cast your public properties and update the model instance.

```php
$product = Product::query()->find(1);
$product->castAndUpdate([
    'name' => 'Product 1',
    'price' => '10.99',
    'category_id' => '1',
    'is_active' => 1,
    'promotion_expires_at' => '2023-12-31 23:59:59',
    'json_column' => ['foo' => 'bar'],
]);
```
