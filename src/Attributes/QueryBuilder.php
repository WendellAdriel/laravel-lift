<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class QueryBuilder
{
    public function __construct(public string $builderClass)
    {
    }
}
