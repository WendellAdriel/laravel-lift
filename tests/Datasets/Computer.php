<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\HasOne;
use WendellAdriel\Lift\Lift;

#[HasOne(Manufacturer::class)]
class Computer extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;
}
