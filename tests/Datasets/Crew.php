<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Lift;

final class Crew extends Model
{
    use Lift;
    use HasUlids;

    #[PrimaryKey(type: 'string', incrementing: false)]
    public string $id;
}
