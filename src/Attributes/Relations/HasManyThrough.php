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
    public string $related;

    /**
     * @var class-string
     */
    public string $through;

    /**
     * @var array<string|class-string>
     */
    public array $arguments = [];

    private ?string $name;

    /**
     * @param  class-string  $related
     * @param  class-string  $through
     * @param  array<string>  ...$arguments
     */
    public function __construct(string $related, string $through, ?string $name = null, string ...$arguments)
    {
        $this->related = $related;
        $this->through = $through;
        $this->name = $name;
        $this->arguments = [$related, $through, ...$arguments];
    }

    public function relationName(): string
    {
        return $this->name ?? Str::plural(Str::camel(class_basename($this->related)));
    }
}
