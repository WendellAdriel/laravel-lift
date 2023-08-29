<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\HasMany;
use WendellAdriel\Lift\Lift;

#[HasMany(LibraryBook::class)]
class Library extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;
}
