<?php

declare(strict_types=1);

namespace WendellAdriel\Lift;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use WendellAdriel\Lift\Concerns\AttributesGuard;
use WendellAdriel\Lift\Concerns\CastValues;
use WendellAdriel\Lift\Concerns\CustomPrimary;
use WendellAdriel\Lift\Concerns\DatabaseConfigurations;
use WendellAdriel\Lift\Concerns\ManageRelations;
use WendellAdriel\Lift\Concerns\RulesValidation;
use WendellAdriel\Lift\Concerns\WatchProperties;
use WendellAdriel\Lift\Exceptions\ImmutablePropertyException;
use WendellAdriel\Lift\Support\PropertyInfo;

trait Lift
{
    use AttributesGuard,
        CastValues,
        CustomPrimary,
        DatabaseConfigurations,
        ManageRelations,
        RulesValidation,
        WatchProperties;

    /**
     * @throws ImmutablePropertyException|ValidationException
     */
    public static function bootLift(): void
    {
        static::saving(function (Model $model) {
            self::syncCostumColumns($model);

            if (! blank($model->getKey())) {
                $immutableProperties = self::immutableProperties();
                foreach ($immutableProperties as $prop) {
                    if ($model->getAttribute($prop) !== $model->{$prop}) {
                        throw new ImmutablePropertyException($prop);
                    }
                }
            }

            $properties = self::getPropertiesWithAtributes($model);
            self::applyValidations($properties);
            self::castValues($model, $properties);

            $publicProperties = self::getModelPublicProperties($model);
            $customColumns = self::customColumns();
            foreach ($publicProperties as $prop) {
                $modelProp = $customColumns[$prop] ?? $prop;
                if (isset($model->{$prop}) && is_null($model->getAttribute($modelProp))) {
                    $model->setAttribute($modelProp, $model->{$prop});
                }
            }

            if (! blank($model->getKey())) {
                $model->dispatchEvents = [];
                $watchedProperties = self::watchedProperties();

                foreach ($watchedProperties as $prop => $event) {
                    if ($model->isDirty($prop)) {
                        $model->dispatchEvents[] = $prop;
                    }
                }
            }

            self::handleRelationsKeys($model);
        });

        static::saved(function (Model $model) {
            self::fillProperties($model);

            foreach ($model->dispatchEvents as $prop) {
                $event = self::watchedProperties()[$prop];
                event(new $event($model));
            }

            $model->dispatchEvents = [];
        });

        static::retrieved(fn (Model $model) => self::fillProperties($model));
    }

    public function syncOriginal(): void
    {
        parent::syncOriginal();
        $this->applyDatabaseConfigurations();
        self::buildRelations($this);

        $properties = self::getPropertiesWithAtributes($this);
        $this->applyPrimaryKey($properties);
        $this->applyAttributesGuard($properties);
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
        $customColumns = self::customColumns();
        $result = [];

        foreach ($publicProperties as $prop) {
            try {
                $modelProp = $customColumns[$prop] ?? $prop;

                if (! blank($model->getKey()) && ! $model->isDirty($prop) && isset($model->{$prop})) {
                    $model->setAttribute($modelProp, $model->{$prop});
                }

                $reflectionProperty = new ReflectionProperty($model, $prop);
                $attributes = $reflectionProperty->getAttributes();

                if (count($attributes) > 0) {
                    $result[] = new PropertyInfo(
                        name: $prop,
                        value: $model->getAttribute($modelProp) ?? null,
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
            if (in_array($property->getName(), self::ignoredProperties())) {
                continue;
            }
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

    /**
     * @param  Collection<PropertyInfo>  $properties
     * @param  class-string  $attributeClass
     */
    private static function getPropertyForAttribute(Collection $properties, string $attributeClass): ?PropertyInfo
    {
        return $properties->first(
            fn ($property) => $property->attributes->contains(
                fn ($attribute) => $attribute->getName() === $attributeClass
            )
        );
    }

    private static function fillProperties(Model $model): void
    {
        self::castValues($model, self::getPropertiesWithAtributes($model));

        foreach ($model->getAttributes() as $key => $value) {
            $model->{$key} = $model->hasCast($key) ? $model->castAttribute($key, $value) : $value;
        }

        self::syncColumnsToCustom($model);
    }
}
