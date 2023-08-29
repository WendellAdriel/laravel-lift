<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Relations;

use Attribute;
use Illuminate\Support\Str;
use WendellAdriel\Lift\Concerns\HasArguments;
use WendellAdriel\Lift\Contracts\RelationAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class MorphMany implements RelationAttribute
{
    use HasArguments;

    /**
     * @var class-string
     */
    public string $relationClass;

    public string $morphName;

    /**
     * @var array<string>
     */
    public array $arguments = [];

    /**
     * @param  class-string  $relationClass
     * @param  array<string>  ...$arguments
     */
    public function __construct(string $relationClass, string $morphName, string ...$arguments)
    {
        $this->relationClass = $relationClass;
        $this->morphName = $morphName;
        $this->arguments = [$relationClass, $morphName, ...$arguments];
    }

    public function relationName(): string
    {
        return Str::plural(Str::camel(class_basename($this->relationClass)));
    }
}
