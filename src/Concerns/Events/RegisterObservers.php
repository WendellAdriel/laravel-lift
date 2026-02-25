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
        $events = [
            'retrieved', 'creating', 'created', 'updating', 'updated',
            'saving', 'saved', 'restoring', 'restored', 'replicating',
            'deleting', 'deleted', 'forceDeleting', 'forceDeleted',
        ];

        foreach (self::modelObservers() as $observer) {
            foreach ($events as $event) {
                if (method_exists($observer, $event)) {
                    static::registerModelEvent($event, $observer . '@' . $event);
                }
            }
        }
    }
}
