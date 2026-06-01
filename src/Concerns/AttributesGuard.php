<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Hidden;
use WendellAdriel\Lift\Attributes\Immutable;
use WendellAdriel\Lift\Support\PropertyInfo;

trait AttributesGuard
{
    private static ?array $immutableProperties = null;

    public static function immutableProperties(): array
    {
        if (is_null(self::$immutableProperties)) {
            self::$immutableProperties = [];
            self::buildImmutableProperties(new static());
        }

        return self::$immutableProperties;
    }

    private static function buildImmutableProperties(Model $model): void
    {
        $properties = self::getPropertiesWithAttributes($model);

        $immutableColumns = self::getPropertiesForAttributes($properties, [Immutable::class]);
        $immutableColumns->each(fn ($property) => self::$immutableProperties[] = $property->name);

        $configColumns = self::getPropertiesForAttributes($properties, [Config::class]);
        $configColumns->each(function ($property) {
            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                return;
            }

            $configAttribute = $configAttribute->newInstance();
            if ($configAttribute->immutable) {
                self::$immutableProperties[] = $property->name;
            }
        });
    }

    /**
     * @param  Collection<PropertyInfo>  $properties
     */
    private function applyAttributesGuard(Collection $properties): void
    {
        $this->mergeGuarded(['*']);
        $customColumns = self::customColumns();

        $fillableProperties = self::getPropertiesForAttributes($properties, [Fillable::class]);
        $this->mergeFillable($fillableProperties
            ->flatMap(fn ($property) => array_unique([$property->name, $customColumns[$property->name] ?? $property->name]))
            ->values()
            ->toArray());

        $hiddenProperties = self::getPropertiesForAttributes($properties, [Hidden::class]);
        $this->makeHidden($hiddenProperties->map(fn ($property) => $property->name)->values()->toArray());

        $configProperties = self::getPropertiesForAttributes($properties, [Config::class]);
        $this->mergeFillable($this->buildLiftList($configProperties, 'fillable'));
        $this->makeHidden([
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
        $customColumns = $attributeProperty === 'fillable' ? self::customColumns() : [];

        $properties->each(function ($property) use (&$result, $attributeProperty, $customColumns) {
            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                return;
            }

            $configAttribute = $configAttribute->newInstance();
            if ($configAttribute->{$attributeProperty}) {
                $result[] = $property->name;

                if (isset($customColumns[$property->name])) {
                    $result[] = $customColumns[$property->name];
                }
            }
        });

        return array_values(array_unique($result));
    }
}
