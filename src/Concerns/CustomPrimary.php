<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Support\Collection;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Support\PropertyInfo;

trait CustomPrimary
{
    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private function applyPrimaryKey(Collection $properties): void
    {
        $primaryKeyProperty = self::getPropertyForAttribute($properties, PrimaryKey::class);
        if (! blank($primaryKeyProperty)) {
            $primaryKeyAttribute = $primaryKeyProperty->attributes->first(fn ($attribute) => $attribute->getName() === PrimaryKey::class);
            if (! blank($primaryKeyAttribute)) {
                $primaryKeyAttribute = $primaryKeyAttribute->newInstance();
                $this->setKeyName($primaryKeyProperty->name);
                $this->setKeyType($primaryKeyAttribute->type);
                $this->setIncrementing($primaryKeyAttribute->incrementing);

                if (! $this->incrementing) {
                    $this->mergeFillable([$primaryKeyProperty->name]);
                }
            }
        }
    }
}
