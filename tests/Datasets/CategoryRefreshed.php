<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Lift;

class CategoryRefreshed extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    public string $title;
}
