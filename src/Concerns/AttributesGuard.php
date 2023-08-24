<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use WendellAdriel\Lift\Attributes\Config;
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

        $configProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        $model->mergeFillable(self::buildLiftList($configProperties, 'fillable'));
        $model->setHidden([
            ...$model->getHidden(),
            ...self::buildLiftList($configProperties, 'hidden'),
        ]);
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private static function buildLiftList(Collection $properties, string $attributeProperty): array
    {
        $result = [];
        $properties->each(function ($property) use (&$result, $attributeProperty) {
            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                return;
            }

            $configAttribute = $configAttribute->newInstance();
            if ($configAttribute->{$attributeProperty}) {
                $result[] = $property->name;
            }
        });

        return $result;
    }
}
