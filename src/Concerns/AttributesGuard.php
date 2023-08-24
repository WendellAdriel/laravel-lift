<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Hidden;
use WendellAdriel\Lift\Support\PropertyInfo;

trait AttributesGuard
{
    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function applyAttributesGuard(Model $model, Collection $properties): void
    {
        $model->mergeGuarded(['*']);

        $fillableProperties = self::getPropertiesForAttributes($properties, [Fillable::class]);
        $model->mergeFillable($fillableProperties->map(fn ($property) => $property->name)->values()->toArray());

        $hiddenProperties = self::getPropertiesForAttributes($properties, [Hidden::class]);
        $model->setHidden($hiddenProperties->map(fn ($property) => $property->name)->values()->toArray());
    }
}
