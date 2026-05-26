# updateValidationMessages

The `updateValidationMessages` method returns an array with all the validation update action messages for your model's **public properties**.

```php
$productRules = Product::updateValidationMessages();

// WILL RETURN
[
    'name' => [
        'required' => 'The PRODUCT NAME field cannot be empty.',
    ],
]
```
