<?php

declare(strict_types=1);

namespace WendellAdriel\Lift;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use WendellAdriel\Lift\Attributes\IgnoreProperties;
use WendellAdriel\Lift\Concerns\AttributesGuard;
use WendellAdriel\Lift\Concerns\CastValues;
use WendellAdriel\Lift\Concerns\CustomPrimary;
use WendellAdriel\Lift\Concerns\DatabaseConfigurations;
use WendellAdriel\Lift\Concerns\Events\ListenerHandler;
use WendellAdriel\Lift\Concerns\Events\RegisterDispatchedEvents;
use WendellAdriel\Lift\Concerns\Events\RegisterObservers;
use WendellAdriel\Lift\Concerns\ManageRelations;
use WendellAdriel\Lift\Concerns\RulesValidation;
use WendellAdriel\Lift\Concerns\WatchProperties;
use WendellAdriel\Lift\Exceptions\ImmutablePropertyException;
use WendellAdriel\Lift\Support\MethodInfo;
use WendellAdriel\Lift\Support\PropertyInfo;

trait Lift
{
    use AttributesGuard,
        CastValues,
        CustomPrimary,
        DatabaseConfigurations,
        ListenerHandler,
        ManageRelations,
        RegisterDispatchedEvents,
        RegisterObservers,
        RulesValidation,
        WatchProperties;

    /**
     * @throws ImmutablePropertyException|ValidationException
     */
    public static function bootLift(): void
    {
        static::registerObservers();
        static::saving(function (Model $model) {
            self::syncCustomColumns($model);

            if (! blank($model->getKey())) {
                $immutableProperties = self::immutableProperties();
                foreach ($immutableProperties as $prop) {
                    if ($model->getAttribute($prop) !== $model->{$prop}) {
                        throw new ImmutablePropertyException($prop);
                    }
                }
            }

            $properties = self::getPropertiesWithAttributes($model);

            self::applyValidations($properties);
            blank($model->getKey())
                ? self::applyCreateValidations($properties)
                : self::applyUpdateValidations($properties);

            self::castValues($model);

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
            self::handleEvent($model, 'saving');
        });

        static::saved(function (Model $model) {
            self::fillProperties($model);

            foreach ($model->dispatchEvents as $prop) {
                $event = self::watchedProperties()[$prop];
                event(new $event($model));
            }

            $model->dispatchEvents = [];
            self::handleEvent($model, 'saved');
        });

        static::retrieved(function (Model $model) {
            self::fillProperties($model);
            self::handleEvent($model, 'retrieved');
        });

        static::creating(fn (Model $model) => self::handleEvent($model, 'creating'));
        static::created(fn (Model $model) => self::handleEvent($model, 'created'));
        static::updating(fn (Model $model) => self::handleEvent($model, 'updating'));
        static::updated(fn (Model $model) => self::handleEvent($model, 'updated'));
        static::deleting(fn (Model $model) => self::handleEvent($model, 'deleting'));
        static::deleted(fn (Model $model) => self::handleEvent($model, 'deleted'));
        static::replicating(fn (Model $model) => self::handleEvent($model, 'replicating'));

        $traitsUsed = class_uses_recursive(new static());
        if (in_array(SoftDeletes::class, $traitsUsed)) {
            static::forceDeleting(fn (Model $model) => self::handleEvent($model, 'forceDeleting'));
            static::forceDeleted(fn (Model $model) => self::handleEvent($model, 'forceDeleted'));
            static::restoring(fn (Model $model) => self::handleEvent($model, 'restoring'));
            static::restored(fn (Model $model) => self::handleEvent($model, 'restored'));
        }
    }

    public function syncOriginal(): void
    {
        parent::syncOriginal();
        $this->registerDispatchedEvents();
        $this->applyDatabaseConfigurations();
        self::buildRelations($this);

        $properties = self::getPropertiesWithAttributes($this);
        $this->applyPrimaryKey($properties);
        $this->applyAttributesGuard($properties);
        self::castValues($this);
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $customColumns = array_flip(self::customColumns());
        $result = [];

        foreach ($array as $key => $value) {
            $result[$customColumns[$key] ?? $key] = $value;
        }

        return $result;
    }

    public function setCreatedAt($value)
    {
        $createdAtColumn = $this->getCreatedAtColumn();

        $this->{$createdAtColumn} = $value;
        $this->setAttribute($createdAtColumn, $value);

        return $this;
    }

    public function setUpdatedAt($value)
    {
        $updatedAtColumn = $this->getUpdatedAtColumn();

        $this->{$updatedAtColumn} = $value;
        $this->setAttribute($updatedAtColumn, $value);

        return $this;
    }

    public function setUniqueIds()
    {
        foreach ($this->uniqueIds() as $column) {
            if (empty($this->{$column})) {
                $uniqueId = $this->newUniqueId();
                $this->{$column} = $uniqueId;
                $this->setAttribute($column, $uniqueId);
            }
        }
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
        parent::setRawAttributes($attributes, $sync);

        foreach ($attributes as $key => $value) {
            $this->{$key} = $this->hasCast($key) ? $this->castAttribute($key, $value) : $value;
        }

        return $this;
    }

    protected static function ignoredProperties(): array
    {
        $reflectionClass = new ReflectionClass(self::class);
        $ignoredProperties = collect($reflectionClass->getAttributes(IgnoreProperties::class))
            ->flatMap(fn (ReflectionAttribute $attribute) => $attribute->getArguments());

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
            ...$ignoredProperties,
        ];
    }

    private static function getPropertiesWithAttributes(Model $model): Collection
    {
        $publicProperties = self::getModelPublicProperties($model);
        $customColumns = self::customColumns();
        $result = [];

        foreach ($publicProperties as $prop) {
            try {
                $modelProp = $customColumns[$prop] ?? $prop;

                $reflectionProperty = new ReflectionProperty($model, $prop);

                if (! blank($model->getKey()) && ! $model->isDirty($prop) && $reflectionProperty->isInitialized($model)) {
                    $model->setAttribute($modelProp, $model->{$prop});
                }

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

    private static function getMethodsWithAttributes(Model $model): Collection
    {
        $publicMethods = self::getModelPublicMethods($model);
        $result = [];

        foreach ($publicMethods as $method) {
            try {

                $reflectionMethod = new ReflectionMethod($model, $method);
                $attributes = $reflectionMethod->getAttributes();

                if (count($attributes) > 0) {
                    $result[] = new MethodInfo(
                        name: $method,
                        method: $reflectionMethod,
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

    private static function getModelPublicMethods(Model $model): array
    {
        $reflectionClass = new ReflectionClass($model);
        $methods = [];

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methods[] = $method->getName();
        }

        return $methods;
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
     * @param  Collection<MethodInfo>  $methods
     * @param  array<string>  $attributes
     * @return Collection<MethodInfo>
     */
    private static function getMethodsForAttributes(Collection $methods, array $attributes): Collection
    {
        return $methods->filter(
            fn ($method) => $method->attributes->contains(
                fn ($attribute) => in_array($attribute->getName(), $attributes)
            )
        );
    }

    private static function getMethodForAttribute(Collection $methods, string $attributeClass): ?MethodInfo
    {
        return $methods->first(
            fn ($method) => $method->attributes->contains(
                fn ($attribute) => $attribute->getName() === $attributeClass
            )
        );
    }

    private static function getMethodsForAttribute(Collection $methods, string $attributeClass): Collection
    {
        return $methods->filter(
            fn ($method) => $method->attributes->contains(
                fn ($attribute) => $attribute->getName() === $attributeClass
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
        self::castValues($model);

        foreach ($model->getAttributes() as $key => $value) {
            $model->{$key} = $model->hasCast($key) ? $model->castAttribute($key, $value) : $value;
        }

        self::syncColumnsToCustom($model);
    }
}
