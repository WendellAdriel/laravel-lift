<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Lift;

final class Movie extends Model
{
    use Lift;

    #[PrimaryKey(type: 'string', incrementing: false)]
    #[Column('movie_id')]
    public string $id;
}
