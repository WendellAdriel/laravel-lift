<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use WendellAdriel\Lift\Attributes\Config;
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
        $data = [];
        $rules = [];
        $messages = [];

        $validatedProperties = self::getPropertiesForAttributes($properties, [Rules::class]);
        $validatedProperties->each(function ($property) use (&$data, &$rules, &$messages) {
            $data[$property->name] = $property->value;
            $rules[$property->name] = [];
            $messages[$property->name] = [];

            $rulesAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Rules::class);
            if (blank($rulesAttribute)) {
                return;
            }

            $rulesAttribute = $rulesAttribute->newInstance();
            $rules[$property->name] = $rulesAttribute->rules;
            $messages[$property->name] = $rulesAttribute->messages;
        });

        $configProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        $configProperties->each(function ($property) use (&$data, &$rules, &$messages) {
            $data[$property->name] = $property->value;
            $rules[$property->name] = [];
            $messages[$property->name] = [];

            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                return;
            }

            $configAttribute = $configAttribute->newInstance();
            $rules[$property->name] = $configAttribute->rules;
            $messages[$property->name] = $configAttribute->messages;
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
