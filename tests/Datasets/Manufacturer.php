<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Lift;

class Manufacturer extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;
}
