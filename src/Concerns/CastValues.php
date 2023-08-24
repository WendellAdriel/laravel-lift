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
    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function castValues(Model $model, Collection $properties): void
    {
        $casts = self::castValuesForCastAttribute($properties);
        $casts = array_merge($casts, self::castValuesForLiftAttribute($properties));

        $model->mergeCasts($casts);
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function castValuesForCastAttribute(Collection $properties): array
    {
        $castableProperties = self::getPropertiesForAttributes($properties, [Cast::class]);
        $casts = [];

        foreach ($castableProperties as $property) {
            $castAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Cast::class);
            if (blank($castAttribute)) {
                continue;
            }

            $casts[$property->name] = $castAttribute->getArguments()[0];
        }

        return $casts;
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function castValuesForLiftAttribute(Collection $properties): array
    {
        $castableProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        $casts = [];

        foreach ($castableProperties as $property) {
            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                continue;
            }

            $configAttribute = $configAttribute->newInstance();
            if (blank($configAttribute->cast)) {
                continue;
            }

            $casts[$property->name] = $configAttribute->cast;
        }

        return $casts;
    }
}
