<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class DB
{
    public function __construct(
        public ?string $connection = null,
        public ?string $table = null,
        public bool $timestamps = true,
    ) {
    }
}
