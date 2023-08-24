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
        static::creating(function (Model $model) {
            $propsWithAttributes = self::getPropertiesWithAtributes($model);

            self::applyValidations($propsWithAttributes);
        });

        static::updating(function (Model $model) {
            $propsWithAttributes = self::getPropertiesWithAtributes($model, $model->getDirty());

            self::applyValidations($propsWithAttributes);
        });

        self::created(function (Model $model) {
            self::fillProperties($model, $model->getAttributes());
        });

        self::updated(function (Model $model) {
            self::fillProperties($model, $model->getDirty());
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

    private static function getPropertiesWithAtributes(Model $model, array $properties = []): Collection
    {
        $publicProperties = self::getModelPublicProperties($model);
        $propertyKeys = array_keys($properties);
        $result = [];

        foreach ($publicProperties as $prop) {
            try {
                if (in_array($prop, self::ignoredProperties())) {
                    continue;
                }

                if ($propertyKeys !== [] && ! in_array($prop, $propertyKeys)) {
                    continue;
                }

                $value = $properties !== []
                    ? $properties[$prop]
                    : $model->getAttribute($prop);

                $reflectionProperty = new ReflectionProperty($model, $prop);
                $attributes = $reflectionProperty->getAttributes();

                if (count($attributes) > 0) {
                    $result[] = new PropertyInfo(
                        name: $prop,
                        value: $value ?? null,
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
