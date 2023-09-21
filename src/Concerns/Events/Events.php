<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns\Events;

use WendellAdriel\Lift\Exceptions\EventDoesNotExistException;

trait Events
{
    private static array $possibleEvents = [
        'retrieved', 'creating', 'created', 'updating', 'updated',
        'saving', 'saved', 'restoring', 'restored', 'replicating',
        'deleting', 'deleted', 'forceDeleting', 'forceDeleted',
    ];

    /**
     * @throws EventDoesNotExistException
     */
    private static function eventExists(string $event): void
    {
        $exists = in_array($event, self::$possibleEvents);
        if (! $exists) {
            throw new EventDoesNotExistException($event);
        }
    }
}
