<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use WendellAdriel\Lift\Attributes\Events\Listener;
use WendellAdriel\Lift\Exceptions\EventDoesNotExistException;

trait ListenerHandler
{
    use Events;

    private static ?array $modelEventMethods = null;

    /**
     * @throws EventDoesNotExistException
     */
    private static function eventHandlerMethods(): array
    {
        if (is_null(self::$modelEventMethods)) {
            self::buildEventHandlers(new static());
        }

        return self::$modelEventMethods;
    }

    /**
     * @throws EventDoesNotExistException
     */
    private static function buildEventHandlers(Model $model): void
    {
        self::$modelEventMethods = [];

        $methods = self::getMethodsWithAttributes($model);

        $methods = self::getMethodsForAttribute($methods, Listener::class);

        foreach ($methods as $method) {
            $attr = $method->attributes->first(fn ($attr) => $attr->getName() === Listener::class)->newInstance();
            if (! empty($attr->event)) {
                self::eventExists($attr->event);
                self::$modelEventMethods[$attr->event] = $method;
            } elseif (str_starts_with($method->name, 'on')) {
                $event = Str::lcfirst(substr($method->name, 2));
                self::eventExists($event);
                self::$modelEventMethods[$event] = $method;
            }
        }

    }

    private static function handleEvent(?Model $model, string $event): void
    {
        if (array_key_exists($event, self::eventHandlerMethods())) {
            $eventHandler = self::eventHandlerMethods()[$event];
            $attr = $eventHandler->attributes->first(fn ($attr) => $attr->getName() === Listener::class)->newInstance();
            $method = $eventHandler->method;
            if (! $attr->queue) {
                $method->invoke($model, $model);
            } else {
                dispatch(fn () => $method->invoke($model, $model));
            }
        }
    }
}
