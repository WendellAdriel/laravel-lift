<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Support\PropertyInfo;

trait CastValues
{
    private static ?array $modelCastableProperties = null;

    public static function castAndCreate(array $properties): self
    {
        $model = new static();

        $model->castAndFill($properties);
        $model->save();

        return $model;
    }

    /**
     * @param  array<string,mixed>  $properties
     */
    public function castAndFill(array $properties): self
    {
        foreach ($properties as $key => $value) {
            $this->{$key} = $this->hasCast($key)
                ? $this->castAttribute($key, $this->getValueForCast($key, $value))
                : $value;
        }

        return $this;
    }

    public function castAndSet(string $property, mixed $value): self
    {
        $this->{$property} = $this->hasCast($property) ? $this->castAttribute($property, $value) : $value;

        return $this;
    }

    public function castAndUpdate(array $properties): self
    {
        $this->castAndFill($properties);
        $this->save();

        return $this;
    }

    private static function castValues(Model $model): void
    {
        $properties = self::getPropertiesWithAttributes($model);
        $casts = self::castableProperties($properties);

        $model->mergeCasts($casts);
        self::$modelCastableProperties = $model->getCasts();
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function castableProperties(Collection $properties): array
    {
        if (is_null(self::$modelCastableProperties)) {
            self::buildCastableProperties($properties);
        }

        return self::$modelCastableProperties;
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function buildCastableProperties(Collection $properties): void
    {
        self::$modelCastableProperties = [];

        $castableProperties = self::getPropertiesForAttributes($properties, [Cast::class]);
        foreach ($castableProperties as $property) {
            $castAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Cast::class);
            if (blank($castAttribute)) {
                continue;
            }

            self::$modelCastableProperties[$property->name] = $castAttribute->getArguments()[0];
        }

        $castableProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        foreach ($castableProperties as $property) {
            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                continue;
            }

            $configAttribute = $configAttribute->newInstance();
            if (blank($configAttribute->cast)) {
                continue;
            }

            self::$modelCastableProperties[$property->name] = $configAttribute->cast;
        }
    }

    private function getValueForCast(string $property, mixed $value): mixed
    {
        $castType = self::$modelCastableProperties[$property] ?? null;

        return match ($castType) {
            'array', 'collection', 'json', 'object' => ! is_string($value) ? json_encode($value) : $value,
            default => $value,
        };
    }
}
