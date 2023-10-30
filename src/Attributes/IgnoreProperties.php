<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class IgnoreProperties
{
    /** @var string[] */
    public array $ignoredProperties;

    public function __construct(
        string $ignoredProperty,
        string ...$additionalIgnoredProperties
    ) {
        $this->ignoredProperties = [$ignoredProperty, ...$additionalIgnoredProperties];
    }
}
