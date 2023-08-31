<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Lift;

class CategoryWithoutCustomQueryBuilder extends Model
{
    use Lift;
}
