<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\MorphTo;
use WendellAdriel\Lift\Lift;

#[MorphTo('imageable')]
class Image extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;
}
