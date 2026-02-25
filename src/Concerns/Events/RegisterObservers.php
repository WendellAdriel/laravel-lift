<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns\Events;

use ReflectionClass;
use WendellAdriel\Lift\Attributes\Events\Observer;

trait RegisterObservers
{
    private static ?array $modelObservers = null;

    private static function modelObservers(): array
    {
        if (is_null(self::$modelObservers)) {
            self::buildModelObservers();
        }

        return self::$modelObservers;
    }

    private static function buildModelObservers(): void
    {
        $classReflection = new ReflectionClass(static::class);
        self::$modelObservers = collect($classReflection->getAttributes(Observer::class))
            ->map(fn ($attr) => $attr->newInstance()->observer)
            ->toArray();
    }

    private static function registerObservers(): void
    {
        foreach (self::modelObservers() as $observer) {
            self::observe($observer);
        }
    }
}
