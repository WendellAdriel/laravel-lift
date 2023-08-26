<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Hidden;
use WendellAdriel\Lift\Lift;

#[DB(table: 'products')]
class ProductHidden extends Model
{
    use Lift;

    public string $name;

    #[Cast('float')]
    public float $price;

    #[Hidden]
    #[Cast('int')]
    public int $random_number;

    #[Cast('immutable_datetime')]
    public CarbonImmutable $expires_at;

    protected $fillable = [
        'name',
        'price',
        'random_number',
        'expires_at',
    ];
}
