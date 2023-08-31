<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Support\PropertyInfo;

trait RulesValidation
{
    private static ?array $modelRules = null;

    private static ?array $modelMessages = null;

    public static function validationRules(): array
    {
        if (is_null(self::$modelRules)) {
            self::$modelRules = [];
            self::$modelMessages = [];
            self::buildValidationRules(new static());
        }

        return self::$modelRules;
    }

    public static function validationMessages(): array
    {
        if (is_null(self::$modelMessages)) {
            self::$modelRules = [];
            self::$modelMessages = [];
            self::buildValidationRules(new static());
        }

        return self::$modelMessages;
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     *
     * @throws ValidationException
     */
    private static function applyValidations(Collection $properties): void
    {
        $validatedProperties = self::getPropertiesForAttributes($properties, [Rules::class]);
        $data = $validatedProperties->mapWithKeys(fn ($property) => [$property->name => $property->value]);

        $configProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        $data = $data->merge($configProperties->mapWithKeys(fn ($property) => [$property->name => $property->value]));

        $validator = Validator::make(
            data: $data->toArray(),
            rules: self::validationRules(),
            messages: self::validationMessages(),
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private static function buildValidationRules(Model $model): void
    {
        $properties = self::getPropertiesWithAtributes($model);

        $validatedProperties = self::getPropertiesForAttributes($properties, [Rules::class]);
        $validatedProperties->each(function ($property) {
            $rulesAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Rules::class);
            if (blank($rulesAttribute)) {
                return;
            }

            $rulesAttribute = $rulesAttribute->newInstance();
            self::$modelRules[$property->name] = $rulesAttribute->rules;
            self::$modelMessages[$property->name] = $rulesAttribute->messages;
        });

        $configProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        $configProperties->each(function ($property) {
            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                return;
            }

            $configAttribute = $configAttribute->newInstance();
            self::$modelRules[$property->name] = $configAttribute->rules;
            self::$modelMessages[$property->name] = $configAttribute->messages;
        });
    }
}
