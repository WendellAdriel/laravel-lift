<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Events;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Listener
{
    public function __construct(public string $event = '', public bool $queue = false)
    {
    }
}
