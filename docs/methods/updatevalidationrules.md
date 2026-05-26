# updateValidationRules

The `updateValidationRules` method returns an array with all the update action validation rules for your model's **public properties**.

```php
$productRules = Product::updateValidationRules();

// WILL RETURN
[
    'name' => ['required', 'string'],
    'price' => ['required', 'numeric'],
]
```
