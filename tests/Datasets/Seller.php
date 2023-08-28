<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\HasOne;
use WendellAdriel\Lift\Attributes\Relations\HasOneThrough;
use WendellAdriel\Lift\Lift;

#[HasOneThrough(Manufacturer::class, Computer::class)]
#[HasOne(Computer::class)]
class Seller extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;
}
