<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

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
    private function applyAttributesGuard(Collection $properties): void
    {
        $this->mergeGuarded(['*']);

        $fillableProperties = self::getPropertiesForAttributes($properties, [Fillable::class]);
        $this->mergeFillable($fillableProperties->map(fn ($property) => $property->name)->values()->toArray());

        $hiddenProperties = self::getPropertiesForAttributes($properties, [Hidden::class]);
        $this->setHidden($hiddenProperties->map(fn ($property) => $property->name)->values()->toArray());

        $configProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        $this->mergeFillable($this->buildLiftList($configProperties, 'fillable'));
        $this->setHidden([
            ...$this->getHidden(),
            ...$this->buildLiftList($configProperties, 'hidden'),
        ]);
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private function buildLiftList(Collection $properties, string $attributeProperty): array
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
