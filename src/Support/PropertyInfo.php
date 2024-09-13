<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Support;

use Illuminate\Support\Collection;
use ReflectionAttribute;

final class PropertyInfo
{
    public function __construct(
        public readonly string $name,
        public readonly mixed $value,
        /**
         * @var Collection<ReflectionAttribute>
         */
        public readonly Collection $attributes,
    ) {}
}
