<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns\Events;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use WendellAdriel\Lift\Attributes\Events\Observer;

trait RegisterObservers
{
    private static ?array $modelObservers = null;

    private static function modelObservers(): array
    {
        if (is_null(self::$modelObservers)) {
            self::buildModelObservers(new static());
        }

        return self::$modelObservers;
    }

    private static function buildModelObservers(Model $model): void
    {

        $classReflection = new ReflectionClass($model);
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
