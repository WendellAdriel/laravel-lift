# validationMessages

The `validationMessages` method returns an array with all the validation messages for your model's **public properties**.

```php
$productRules = Product::validationMessages();

// WILL RETURN
[
    'name' => [
        'required' => 'The PRODUCT NAME field cannot be empty.',
    ],
]
```
