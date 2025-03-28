<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Attributes\Relations;

use Attribute;
use Illuminate\Support\Str;
use WendellAdriel\Lift\Concerns\HasArguments;
use WendellAdriel\Lift\Concerns\HasPivot;
use WendellAdriel\Lift\Contracts\PivotAttribute;
use WendellAdriel\Lift\Contracts\RelationAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class BelongsToMany implements PivotAttribute, RelationAttribute
{
    use HasArguments;
    use HasPivot;

    /**
     * @var class-string
     */
    public string $related;

    public ?string $pivotModel;

    /**
     * @var array<string>|null
     */
    public ?array $pivotColumns;

    public ?bool $pivotTimestamps;

    /**
     * @var array<string>
     */
    public array $arguments = [];

    private ?string $name;

    /**
     * @param  class-string  $related
     * @param  array<string>|null  $pivotColumns
     */
    public function __construct(string $related, ?string $name = null, ?string $pivotModel = null, ?array $pivotColumns = null, ?bool $pivotTimestamps = null, string ...$arguments)
    {
        $this->related = $related;
        $this->name = $name;
        $this->arguments = [$related, ...$arguments];
        $this->pivotModel = $pivotModel;
        $this->pivotColumns = $pivotColumns;
        $this->pivotTimestamps = $pivotTimestamps;
    }

    public function relationName(): string
    {
        return $this->name ?? Str::plural(Str::camel(class_basename($this->related)));
    }
}
