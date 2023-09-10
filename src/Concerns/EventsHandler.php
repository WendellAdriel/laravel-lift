<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Events\Created;
use WendellAdriel\Lift\Attributes\Events\Creating;
use WendellAdriel\Lift\Attributes\Events\Deleted;
use WendellAdriel\Lift\Attributes\Events\Deleting;
use WendellAdriel\Lift\Attributes\Events\ForceDeleted;
use WendellAdriel\Lift\Attributes\Events\ForceDeleting;
use WendellAdriel\Lift\Attributes\Events\Replicating;
use WendellAdriel\Lift\Attributes\Events\Restored;
use WendellAdriel\Lift\Attributes\Events\Restoring;
use WendellAdriel\Lift\Attributes\Events\Retrieved;
use WendellAdriel\Lift\Attributes\Events\Saved;
use WendellAdriel\Lift\Attributes\Events\Saving;
use WendellAdriel\Lift\Attributes\Events\Updated;
use WendellAdriel\Lift\Attributes\Events\Updating;

trait EventsHandler
{
    private static ?array $modelEventMethods = null;

    private static function eventHandlerMethods(): array
    {
        if (is_null(self::$modelEventMethods)) {
            self::buildEventHandlers(new static());
        }

        return self::$modelEventMethods;
    }

    private static function buildEventHandlers(Model $model): void
    {
        self::$modelEventMethods = [];

        $methods = self::getMethodsWithAttributes($model);

        self::$modelEventMethods['retrieved'] = self::getMethodForAttribute($methods, Retrieved::class);
        self::$modelEventMethods['creating'] = self::getMethodForAttribute($methods, Creating::class);
        self::$modelEventMethods['created'] = self::getMethodForAttribute($methods, Created::class);
        self::$modelEventMethods['updating'] = self::getMethodForAttribute($methods, Updating::class);
        self::$modelEventMethods['updated'] = self::getMethodForAttribute($methods, Updated::class);
        self::$modelEventMethods['saving'] = self::getMethodForAttribute($methods, Saving::class);
        self::$modelEventMethods['saved'] = self::getMethodForAttribute($methods, Saved::class);
        self::$modelEventMethods['deleting'] = self::getMethodForAttribute($methods, Deleting::class);
        self::$modelEventMethods['deleted'] = self::getMethodForAttribute($methods, Deleted::class);
        self::$modelEventMethods['forceDeleting'] = self::getMethodForAttribute($methods, ForceDeleting::class);
        self::$modelEventMethods['forceDeleted'] = self::getMethodForAttribute($methods, ForceDeleted::class);
        self::$modelEventMethods['restoring'] = self::getMethodForAttribute($methods, Restoring::class);
        self::$modelEventMethods['restored'] = self::getMethodForAttribute($methods, Restored::class);
        self::$modelEventMethods['replicating'] = self::getMethodForAttribute($methods, Replicating::class);
    }

    private static function handleEvent(?Model $model, string $event): void
    {
        self::eventHandlerMethods()[$event]?->method->invoke($model, $model);
    }
}
