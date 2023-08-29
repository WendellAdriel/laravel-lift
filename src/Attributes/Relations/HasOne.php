<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Relations;

use Attribute;
use Illuminate\Support\Str;
use WendellAdriel\Lift\Concerns\HasArguments;
use WendellAdriel\Lift\Contracts\RelationAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class HasOne implements RelationAttribute
{
    use HasArguments;

    /**
     * @var class-string
     */
    public string $relationClass;

    /**
     * @var array<string>
     */
    public array $arguments = [];

    /**
     * @param  class-string  $relationClass
     * @param  array<string>  ...$arguments
     */
    public function __construct(string $relationClass, string ...$arguments)
    {
        $this->relationClass = $relationClass;
        $this->arguments = [$relationClass, ...$arguments];
    }

    public function relationName(): string
    {
        return Str::singular(Str::camel(class_basename($this->relationClass)));
    }
}
