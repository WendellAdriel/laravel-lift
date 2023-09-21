<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
            ->map(fn ($attr) => $attr->newInstance())
            ->map(function ($attrInstance) {
                if (! empty($attrInstance->event)) {
                    return $attrInstance;
                }
                $shortName = (new ReflectionClass($attrInstance->eventClass))->getShortName();
                $event = collect(self::$possibleEvents)->first(fn ($event) => Str::contains($shortName, Str::ucfirst($event)));
                if (is_null($event)) {
                    throw new EventDoesNotExistException("no valid event found in: {$shortName}");
                }
                $attrInstance->event = $event;

                return $attrInstance;
            })
            ->flatMap(fn ($attr) => [$attr->event => $attr->eventClass])
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
