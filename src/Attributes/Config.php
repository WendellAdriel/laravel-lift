<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Config
{
    public function __construct(
        public ?string $cast = null,
        public bool $fillable = false,
        public bool $hidden = false,
        /**
         * @var array<string>
         */
        public array $rules = [],
        /**
         * @var array<string, string>
         */
        public array $messages = [],
    ) {
    }
}
