<?php

declare(strict_types=1);

namespace WendellAdriel\Lift;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use WendellAdriel\Lift\Concerns\AttributesGuard;
use WendellAdriel\Lift\Concerns\CastValues;
use WendellAdriel\Lift\Concerns\RulesValidation;
use WendellAdriel\Lift\Support\PropertyInfo;

trait Lift
{
    use RulesValidation, CastValues, AttributesGuard;

    public static function bootLift(): void
    {
        static::saving(function (Model $model) {
            $properties = self::getPropertiesWithAtributes($model);

            self::applyValidations($properties);
            self::castValues($model, $properties);
        });

        static::saved(function (Model $model) {
            $properties = self::getPropertiesWithAtributes($model);

            self::castValues($model, $properties);
            self::fillProperties($model, $model->getAttributes());
        });
    }

    public function syncOriginal(): void
    {
        parent::syncOriginal();

        self::applyAttributesGuard($this, self::getPropertiesWithAtributes($this));
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

    /**
     * @param  Collection<PropertyInfo>  $properties
     * @param  array<string>  $attributes
     * @return Collection<PropertyInfo>
     */
    private static function getPropertiesForAttributes(Collection $properties, array $attributes): Collection
    {
        return $properties->filter(
            fn ($property) => $property->attributes->contains(
                fn ($attribute) => in_array($attribute->getName(), $attributes)
            )
        );
    }

    private static function fillProperties(Model $model, array $properties): void
    {
        foreach ($properties as $key => $value) {
            $model->{$key} = $model->hasCast($key) ? $model->castAttribute($key, $value) : $value;
        }
    }
}
