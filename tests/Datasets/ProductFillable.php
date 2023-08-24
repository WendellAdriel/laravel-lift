<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Lift;

class ProductFillable extends Model
{
    use Lift;

    #[Fillable]
    public string $name;

    #[Fillable]
    #[Cast('float')]
    public float $price;

    #[Fillable]
    #[Cast('int')]
    public int $random_number;

    #[Fillable]
    #[Cast('immutable_datetime')]
    public CarbonImmutable $expires_at;

    protected $table = 'products';
}
