<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Exceptions;

use Exception;

final class ImmutablePropertyException extends Exception
{
    public function __construct(string $property)
    {
        parent::__construct("Cannot update immutable property: {$property}");
    }
}
