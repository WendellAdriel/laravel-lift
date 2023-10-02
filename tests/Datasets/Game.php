<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Lift;

final class Game extends Model
{
    use HasUuids;
    use Lift;

    #[PrimaryKey(type: 'string', incrementing: false)]
    public string $id;
}
