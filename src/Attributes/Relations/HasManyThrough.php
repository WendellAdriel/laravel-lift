<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Relations;

use Attribute;
use Illuminate\Support\Str;
use WendellAdriel\Lift\Concerns\HasArguments;
use WendellAdriel\Lift\Contracts\RelationAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class HasManyThrough implements RelationAttribute
{
    use HasArguments;

    /**
     * @var class-string
     */
    public string $relationClass;

    /**
     * @var class-string
     */
    public string $throughClass;

    /**
     * @var array<string>
     */
    public array $arguments = [];

    /**
     * @param  class-string  $relationClass
     * @param  class-string  $throughClass
     * @param  array<string>  ...$arguments
     */
    public function __construct(string $relationClass, string $throughClass, string ...$arguments)
    {
        $this->relationClass = $relationClass;
        $this->throughClass = $throughClass;
        $this->arguments = [$relationClass, $throughClass, ...$arguments];
    }

    public function relationName(): string
    {
        return Str::plural(Str::camel(class_basename($this->relationClass)));
    }
}
