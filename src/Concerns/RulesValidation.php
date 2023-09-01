<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\CreateRules;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Attributes\UpdateRules;
use WendellAdriel\Lift\Support\PropertyInfo;

trait RulesValidation
{
    private static ?array $modelRules = null;

    private static ?array $modelCreateRules = null;

    private static ?array $modelUpdateRules = null;

    private static ?array $modelMessages = null;

    private static ?array $modelCreateMessages = null;

    private static ?array $modelUpdateMessages = null;

    public static function validationRules(): array
    {
        if (is_null(self::$modelRules)) {
            self::buildValidationRules(new static());
        }

        return self::$modelRules;
    }

    public static function createValidationRules(): array
    {
        if (is_null(self::$modelCreateRules)) {
            self::buildValidationRules(new static());
        }

        return self::$modelCreateRules;
    }

    public static function updateValidationRules(): array
    {
        if (is_null(self::$modelUpdateRules)) {
            self::buildValidationRules(new static());
        }

        return self::$modelUpdateRules;
    }

    public static function validationMessages(): array
    {
        if (is_null(self::$modelMessages)) {
            self::buildValidationRules(new static());
        }

        return self::$modelMessages;
    }

    public static function createValidationMessages(): array
    {
        if (is_null(self::$modelCreateMessages)) {
            self::buildValidationRules(new static());
        }

        return self::$modelCreateMessages;
    }

    public static function updateValidationMessages(): array
    {
        if (is_null(self::$modelUpdateMessages)) {
            self::buildValidationRules(new static());
        }

        return self::$modelUpdateMessages;
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

    /**
     * @param  Collection<PropertyInfo>  $properties
     *
     * @throws ValidationException
     */
    private static function applyCreateValidations(Collection $properties): void
    {
        $validatedProperties = self::getPropertiesForAttributes($properties, [CreateRules::class]);
        $data = $validatedProperties->mapWithKeys(fn ($property) => [$property->name => $property->value]);

        $validator = Validator::make(
            data: $data->toArray(),
            rules: self::createValidationRules(),
            messages: self::createValidationMessages(),
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     *
     * @throws ValidationException
     */
    private static function applyUpdateValidations(Collection $properties): void
    {
        $validatedProperties = self::getPropertiesForAttributes($properties, [UpdateRules::class]);
        $data = $validatedProperties->mapWithKeys(fn ($property) => [$property->name => $property->value]);

        $validator = Validator::make(
            data: $data->toArray(),
            rules: self::updateValidationRules(),
            messages: self::updateValidationMessages(),
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private static function buildValidationRules(Model $model): void
    {
        self::$modelRules = [];
        self::$modelCreateRules = [];
        self::$modelUpdateRules = [];
        self::$modelMessages = [];
        self::$modelCreateMessages = [];
        self::$modelUpdateMessages = [];

        $properties = self::getPropertiesWithAttributes($model);

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

        $validatedCreateProperties = self::getPropertiesForAttributes($properties, [CreateRules::class]);
        $validatedCreateProperties->each(function ($property) {
            $rulesAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === CreateRules::class);
            if (blank($rulesAttribute)) {
                return;
            }

            $rulesAttribute = $rulesAttribute->newInstance();
            self::$modelCreateRules[$property->name] = $rulesAttribute->rules;
            self::$modelCreateMessages[$property->name] = $rulesAttribute->messages;
        });

        $validatedUpdateProperties = self::getPropertiesForAttributes($properties, [UpdateRules::class]);
        $validatedUpdateProperties->each(function ($property) {
            $rulesAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === UpdateRules::class);
            if (blank($rulesAttribute)) {
                return;
            }

            $rulesAttribute = $rulesAttribute->newInstance();
            self::$modelUpdateRules[$property->name] = $rulesAttribute->rules;
            self::$modelUpdateMessages[$property->name] = $rulesAttribute->messages;
        });
    }
}
