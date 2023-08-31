<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\QueryBuilder;
use WendellAdriel\Lift\Lift;

#[QueryBuilder(CategoryQueryBuilder::class)]
class Category extends Model
{
    use Lift;
}
