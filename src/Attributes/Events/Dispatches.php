<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Events;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class Dispatches
{
    public function __construct(
        public string $eventClass,
        public string $event = ''
    ) {
    }
}
