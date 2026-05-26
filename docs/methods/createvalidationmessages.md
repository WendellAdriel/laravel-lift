# createValidationMessages

The `createValidationMessages` method returns an array with all the validation create action messages for your model's **public properties**.

```php
$productRules = Product::createValidationMessages();

// WILL RETURN
[
    'name' => [
        'required' => 'The PRODUCT NAME field cannot be empty.',
    ],
]
```
