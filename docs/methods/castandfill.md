# castAndFill

A replacement for the `fill` method. It will cast your public properties and fill the model instance.

```php
$product = new Product();
$product->castAndFill([
    'name' => 'Product 1',
    'price' => '10.99',
    'category_id' => '1',
    'is_active' => 1,
    'promotion_expires_at' => '2023-12-31 23:59:59',
    'json_column' => '{"foo":"bar"}', // You can also pass a JSON string
]);
$product->save();
```
