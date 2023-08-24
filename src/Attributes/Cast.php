<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Cast
{
    public function __construct(public string $type)
    {
    }
}
