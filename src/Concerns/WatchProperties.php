<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\Watch;

trait WatchProperties
{
    private static ?array $watchedProperties = null;

    private array $dispatchEvents = [];

    public static function watchedProperties(): array
    {
        if (is_null(self::$watchedProperties)) {
            self::$watchedProperties = [];
            self::buildWatchedProperties(new static());
        }

        return self::$watchedProperties;
    }

    private static function buildWatchedProperties(Model $model): void
    {
        $properties = self::getPropertiesWithAtributes($model);

        $watchedColumns = self::getPropertiesForAttributes($properties, [Watch::class]);
        $watchedColumns->each(function ($property) {
            $watchAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Watch::class);
            if (blank($watchAttribute)) {
                return;
            }

            $watchAttribute = $watchAttribute->newInstance();
            if (! is_null($watchAttribute->event)) {
                self::$watchedProperties[$property->name] = $watchAttribute->event;
            }
        });

        $configColumns = self::getPropertiesForAttributes($properties, [Config::class]);
        $configColumns->each(function ($property) {
            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                return;
            }

            $configAttribute = $configAttribute->newInstance();
            if (! is_null($configAttribute->watch)) {
                self::$watchedProperties[$property->name] = $configAttribute->watch;
            }
        });
    }
}
