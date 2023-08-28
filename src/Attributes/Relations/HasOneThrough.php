<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Relations;

use Attribute;
use Illuminate\Support\Str;
use WendellAdriel\Lift\Concerns\HasArguments;
use WendellAdriel\Lift\Contracts\RelationAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class HasOneThrough implements RelationAttribute
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
     * @var array<mixed>
     */
    public array $arguments = [];

    /**
     * @param  class-string  $relationClass
     * @param  class-string  $throughClass
     * @param  array<mixed>  ...$arguments
     */
    public function __construct(string $relationClass, string $throughClass, array ...$arguments)
    {
        $this->relationClass = $relationClass;
        $this->throughClass = $throughClass;
        $this->arguments = [$relationClass, $throughClass, ...$arguments];
    }

    public function relationName(): string
    {
        return Str::singular(mb_strtolower(class_basename($this->relationClass)));
    }
}
