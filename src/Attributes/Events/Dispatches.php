<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Events;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class Dispatches
{
    public function __construct(public string $event, public string $eventClass)
    {
    }
}
