# createValidationRules

The `createValidationRules` method returns an array with all the create action validation rules for your model's **public properties**.

```php
$productRules = Product::createValidationRules();

// WILL RETURN
[
    'name' => ['required', 'string'],
    'price' => ['required', 'numeric'],
]
```
