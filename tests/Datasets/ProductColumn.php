<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Lift;

#[DB(table: 'products')]
class ProductColumn extends Model
{
    use Lift;

    public string $name;

    #[Cast('float')]
    #[Config(column: 'price')]
    public float $product_price;

    #[Cast('int')]
    #[Column(name: 'random_number')]
    public int $number;

    #[Cast('immutable_datetime')]
    public CarbonImmutable $expires_at;

    #[Cast('array')]
    public ?array $json_column;

    protected $fillable = [
        'name',
        'price',
        'random_number',
        'expires_at',
        'json_column',
    ];
}
