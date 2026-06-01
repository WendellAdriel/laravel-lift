<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;
use WendellAdriel\Lift\Lift;

#[DB(table: 'posts')]
#[BelongsTo(related: User::class, name: 'author')]
class NullableConfigPost extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Config(fillable: true, rules: ['required', 'string'])]
    public string $title;

    #[Config(fillable: true, rules: ['required', 'string'])]
    public string $content;

    #[Config(fillable: true, rules: ['nullable', 'integer'], cast: 'integer', default: null)]
    public ?int $user_id;
}
