<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Support\PropertyInfo;

trait RulesValidation
{
    /**
     * @param  Collection<PropertyInfo>  $properties
     *
     * @throws ValidationException
     */
    private static function applyValidations(Collection $properties): void
    {
        $validatedProperties = self::getPropertiesForAttributes($properties, [Rules::class, Password::class]);
        $data = [];
        $rules = [];
        $messages = [];
        $validatedProperties->each(function ($property) use (&$data, &$rules, &$messages) {
            $data[$property->name] = $property->value;
            $rules[$property->name] = [];
            $messages[$property->name] = [];

            $rulesAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Rules::class);
            if (! blank($rulesAttribute)) {
                $rulesArguments = $rulesAttribute->getArguments();
                $rules[$property->name] = $rulesArguments[0];
                $messages[$property->name] = $rulesArguments[1] ?? [];
            }
        });

        $validator = Validator::make(
            data: $data,
            rules: $rules,
            messages: $messages,
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
