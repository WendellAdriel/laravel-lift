# validationRules

The `validationRules` method returns an array with all the validation rules for your model's **public properties**.

```php
$productRules = Product::validationRules();

// WILL RETURN
[
    'name' => ['required', 'string'],
    'price' => ['required', 'numeric'],
    'random_number' => ['required', 'integer'],
    'expires_at' => ['required', 'date_format:Y-m-d H:i:s'],
]
```
