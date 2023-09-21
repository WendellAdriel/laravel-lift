<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Exceptions;

use Exception;

final class EventDoesNotExistException extends Exception
{
    public function __construct(string $event)
    {
        parent::__construct("Cannot register listener for unknown event: {$event}");
    }
}
