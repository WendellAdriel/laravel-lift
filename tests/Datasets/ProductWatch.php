<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Watch;
use WendellAdriel\Lift\Lift;

#[DB(table: 'products')]
class ProductWatch extends Model
{
    use Lift;

    #[Fillable]
    public string $name;

    #[Watch(PriceChangedEvent::class)]
    #[Fillable]
    #[Cast('float')]
    public float $price;

    #[Config(cast: 'int', fillable: true, watch: RandomNumberChangedEvent::class)]
    public int $random_number;

    #[Fillable]
    #[Cast('immutable_datetime')]
    public CarbonImmutable $expires_at;
}
