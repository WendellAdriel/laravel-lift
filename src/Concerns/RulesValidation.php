<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use UnitEnum;
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

        return self::formatValidationMessages(self::$modelMessages);
    }

    public static function createValidationMessages(): array
    {
        if (is_null(self::$modelCreateMessages)) {
            self::buildValidationRules(new static());
        }

        return self::formatValidationMessages(self::$modelCreateMessages);
    }

    public static function updateValidationMessages(): array
    {
        if (is_null(self::$modelUpdateMessages)) {
            self::buildValidationRules(new static());
        }

        return self::formatValidationMessages(self::$modelUpdateMessages);
    }

    /**
     * Return a scalar value for the given value that might be an enum.
     *
     * @internal
     *
     * @template TValue
     * @template TDefault
     *
     * @param  TValue  $value
     * @param  TDefault|callable(TValue): TDefault  $default
     * @return ($value is empty ? TDefault : mixed)
     */
    private static function enumValue($value, $default = null)
    {
        if (function_exists('Illuminate\Support\enum_value')) {
            return \Illuminate\Support\enum_value($value, $default);
        }

        return transform($value, fn ($value) => match (true) {
            $value instanceof BackedEnum => $value->value,
            $value instanceof UnitEnum => $value->name,

            default => $value,
        }, $default ?? $value);
    }

    /**
     * Parse rules to get advanced implementation
     */
    private static function parseValidationRules(Model $model, array $properties): array
    {
        foreach ($properties as $key => $rules) {
            $properties[$key] = array_map(function ($rule) use ($model) {
                if (method_exists($model, $rule)) {
                    $rule = $model->{$rule}($model);
                }

                return $rule;
            }, $rules);
        }

        return $properties;
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     *
     * @throws ValidationException
     */
    private static function applyValidations(Model $model, Collection $properties): void
    {
        self::buildValidationRules(new static());

        $validatedProperties = self::getPropertiesForAttributes(
            $properties,
            [
                Rules::class,
                blank($model->getKey()) ? CreateRules::class : UpdateRules::class,
            ]
        );
        $data = $validatedProperties->mapWithKeys(fn ($property) => [$property->name => static::enumValue($property->value)]);

        $configProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        $data = $data->merge($configProperties->mapWithKeys(fn ($property) => [$property->name => static::enumValue($property->value)]));

        $validator = Validator::make(
            data: $data->toArray(),
            rules: self::parseValidationRules($model, [
                ...self::validationRules(),
                ...(blank($model->getKey()) ? self::createValidationRules() : self::updateValidationRules()),
            ]),
            messages: [
                ...self::validationMessages(),
                ...(blank($model->getKey()) ? self::createValidationMessages() : self::updateValidationMessages()),
            ],
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
        $data = $validatedProperties->mapWithKeys(fn ($property) => [$property->name => static::enumValue($property->value)]);

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
        $data = $validatedProperties->mapWithKeys(fn (PropertyInfo $property) => [$property->name => static::enumValue($property->value)]);

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

    private static function formatValidationMessages(array $messages): array
    {
        return collect($messages)
            ->filter(fn ($messagesList) => ! blank($messagesList))
            ->map(fn ($messagesList) => collect($messagesList)->map(fn ($message) => __($message))->toArray())
            ->toArray();
    }
}
