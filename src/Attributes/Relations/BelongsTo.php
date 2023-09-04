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
    public string $related;

    /**
     * @var array<string>
     */
    public array $arguments = [];

    private ?string $name;

    /**
     * @param  class-string  $related
     * @param  array<string>  ...$arguments
     */
    public function __construct(string $related, ?string $name = null, string ...$arguments)
    {
        $this->related = $related;
        $this->name = $name;
        $this->arguments = [$related, ...$arguments];

        $this->arguments = array_pad($this->arguments, 4, null);

        if ($this->arguments[1] === null) {
            $this->arguments[1] = Str::snake(class_basename($this->related)) . '_id';
        }

        if ($this->arguments[2] === null) {
            $this->arguments[2] = 'id';
        }

        $this->arguments[3] = $this->relationName();
    }

    public function relationName(): string
    {
        return $this->name ?? Str::singular(Str::camel(class_basename($this->related)));
    }
}
