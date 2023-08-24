<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Support\PropertyInfo;

trait CastValues
{
    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function castValues(Model $model, Collection $properties): void
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

        $model->mergeCasts($casts);
    }
}
