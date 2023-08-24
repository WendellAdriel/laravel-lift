<?php

declare(strict_types=1);

namespace WendellAdriel\Lift;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use WendellAdriel\Lift\Concerns\RulesValidation;
use WendellAdriel\Lift\Support\PropertyInfo;

trait Lift
{
    use RulesValidation;

    public static function bootLift(): void
    {
        static::saving(function (Model $model) {
            self::applyValidations(self::getPropertiesWithAtributes($model));
        });

        static::saved(function (Model $model) {
            self::fillProperties($model, $model->getAttributes());
        });
    }

    protected static function ignoredProperties(): array
    {
        return [
            'incrementing',
            'preventsLazyLoading',
            'exists',
            'wasRecentlyCreated',
            'snakeAttributes',
            'encrypter',
            'manyMethods',
            'timestamps',
            'usesUniqueIds',
        ];
    }

    private static function getPropertiesWithAtributes(Model $model): Collection
    {
        $publicProperties = self::getModelPublicProperties($model);
        $result = [];

        foreach ($publicProperties as $prop) {
            try {
                if (in_array($prop, self::ignoredProperties())) {
                    continue;
                }

                if (! blank($model->getKey()) && ! $model->isDirty($prop)) {
                    $model->setAttribute($prop, $model->{$prop});
                }

                $reflectionProperty = new ReflectionProperty($model, $prop);
                $attributes = $reflectionProperty->getAttributes();

                if (count($attributes) > 0) {
                    $result[] = new PropertyInfo(
                        name: $prop,
                        value: $model->getAttribute($prop) ?? null,
                        attributes: collect($attributes),
                    );
                }
            } catch (ReflectionException) {
                continue;
            }
        }

        return collect($result);
    }

    private static function getModelPublicProperties(Model $model): array
    {
        $reflectionClass = new ReflectionClass($model);
        $properties = [];

        foreach ($reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $properties[] = $property->getName();
        }

        return $properties;
    }

    private static function fillProperties(Model $model, array $properties): void
    {
        foreach ($properties as $key => $value) {
            $model->{$key} = $value;
        }
    }
}
