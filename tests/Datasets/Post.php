<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;
use WendellAdriel\Lift\Lift;

#[BelongsTo(User::class)]
class Post extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    public string $title;

    #[Fillable]
    public string $content;

    #[Fillable]
    public ?int $user_id;
}
