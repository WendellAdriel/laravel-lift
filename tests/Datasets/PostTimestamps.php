<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Lift;

#[DB(table: 'posts')]
class PostTimestamps extends Model
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

    #[Cast('datetime')]
    public Carbon $created_at;

    #[Cast('datetime')]
    public Carbon $updated_at;
}
