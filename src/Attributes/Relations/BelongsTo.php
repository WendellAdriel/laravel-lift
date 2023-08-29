<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Relations;

use Attribute;
use Illuminate\Support\Str;
use WendellAdriel\Lift\Concerns\HasArguments;
use WendellAdriel\Lift\Contracts\RelationAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class BelongsTo implements RelationAttribute
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

        $this->arguments = array_pad($this->arguments, 4, null);
        if ($this->arguments[3] === null) {
            $this->arguments[3] = $this->relationName();
        }
    }

    public function relationName(): string
    {
        return Str::singular(Str::camel(class_basename($this->relationClass)));
    }
}
