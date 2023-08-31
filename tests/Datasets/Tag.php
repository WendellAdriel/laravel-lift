<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\MorphedByMany;
use WendellAdriel\Lift\Lift;

#[MorphedByMany(Post::class, 'taggable')]
class Tag extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;
}
