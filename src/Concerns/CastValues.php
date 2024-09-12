<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Support\PropertyInfo;

trait CastValues
{
    protected static array $modelCastableProperties = [];

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
        self::$modelCastableProperties[static::class] = $model->getCasts();
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function castableProperties(Collection $properties): array
    {
        if (! isset(self::$modelCastableProperties[static::class])) {
            self::buildCastableProperties($properties);
        }

        return self::$modelCastableProperties[static::class];
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function buildCastableProperties(Collection $properties): void
    {
        self::$modelCastableProperties[static::class] = [];

        $castableProperties = self::getPropertiesForAttributes($properties, [Cast::class]);
        foreach ($castableProperties as $property) {
            $castAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Cast::class);
            if (blank($castAttribute)) {
                continue;
            }

            $castAttribute = $castAttribute->newInstance();
            if (blank($castAttribute->type)) {
                continue;
            }

            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (filled($configAttribute)) {
                $configAttribute = $configAttribute->newInstance();
                if (filled($configAttribute->column)) {
                    self::$modelCastableProperties[static::class][$configAttribute->column] = $castAttribute->type;

                    continue;
                }
            }

            $columnAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Column::class);
            if (filled($columnAttribute)) {
                $columnAttribute = $columnAttribute->newInstance();
                if (filled($columnAttribute->name)) {
                    self::$modelCastableProperties[static::class][$columnAttribute->name] = $castAttribute->type;

                    continue;
                }
            }

            self::$modelCastableProperties[static::class][$property->name] = $castAttribute->type;
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

            if (filled($configAttribute->column)) {
                self::$modelCastableProperties[static::class][$configAttribute->column] = $configAttribute->cast;

                continue;
            }

            $columnAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Column::class);
            if (filled($columnAttribute)) {
                $columnAttribute = $columnAttribute->newInstance();
                if (filled($columnAttribute->name)) {
                    self::$modelCastableProperties[static::class][$columnAttribute->name] = $configAttribute->cast;

                    continue;
                }
            }

            self::$modelCastableProperties[static::class][$property->name] = $configAttribute->cast;
        }
    }

    private function getValueForCast(string $property, mixed $value): mixed
    {
        $castType = self::$modelCastableProperties[static::class][$property] ?? null;

        return match ($castType) {
            'array', 'collection', 'json', 'object' => ! is_string($value) ? json_encode($value) : $value,
            default => $value,
        };
    }
}
