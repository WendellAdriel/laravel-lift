<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Relations;

use Attribute;
use Illuminate\Support\Str;
use WendellAdriel\Lift\Concerns\HasArguments;
use WendellAdriel\Lift\Contracts\RelationAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class BelongsToMany implements RelationAttribute
{
    use HasArguments;

    /**
     * @var class-string
     */
    public string $relationClass;

    /**
     * @var array<mixed>
     */
    public array $arguments = [];

    /**
     * @param  class-string  $relationClass
     * @param  array<mixed>  ...$arguments
     */
    public function __construct(string $relationClass, array ...$arguments)
    {
        $this->relationClass = $relationClass;
        $this->arguments = [$relationClass, ...$arguments];
    }

    public function relationName(): string
    {
        return Str::plural(Str::camel(class_basename($this->relationClass)));
    }
}
