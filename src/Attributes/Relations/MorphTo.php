<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Relations;

use Attribute;
use WendellAdriel\Lift\Concerns\HasArguments;
use WendellAdriel\Lift\Contracts\RelationAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class MorphTo implements RelationAttribute
{
    use HasArguments;

    public string $morphName;

    /**
     * @var array<string|class-string>
     */
    public array $arguments = [];

    /**
     * @param  array<string>  ...$arguments
     */
    public function __construct(string $morphName, string ...$arguments)
    {
        $this->morphName = $morphName;
        $this->arguments = [$morphName, ...$arguments];
    }

    public function relationName(): string
    {
        return $this->morphName;
    }
}
