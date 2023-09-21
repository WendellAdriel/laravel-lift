<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns\Events;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use WendellAdriel\Lift\Attributes\Events\Dispatches;
use WendellAdriel\Lift\Exceptions\EventDoesNotExistException;

trait RegisterDispatchedEvents
{
    use Events;
    private static ?array $modelDispatchEvents = null;

    /**
     * @throws EventDoesNotExistException
     */
    private static function modelDispatchEvents(Model $model): array
    {
        if (is_null(self::$modelDispatchEvents)) {
            self::buildModelDispatchEvents($model);
        }

        return self::$modelDispatchEvents;
    }

    /**
     * @throws EventDoesNotExistException
     */
    private static function buildModelDispatchEvents(Model $model): void
    {

        $classReflection = new ReflectionClass($model);
        self::$modelDispatchEvents = collect($classReflection->getAttributes(Dispatches::class))
            ->map(fn($attr) => $attr->newInstance())
            ->flatMap(fn($attr) => [$attr->event => $attr->eventClass])
            ->toArray();

    }

    /**
     * @throws EventDoesNotExistException
     */
    private function registerDispatchedEvents(): void
    {
        $this->dispatchesEvents = [...self::modelDispatchEvents($this), ...$this->dispatchesEvents];
    }
}
