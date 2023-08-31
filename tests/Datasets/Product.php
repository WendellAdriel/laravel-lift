<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Lift;

class Product extends Model
{
    use Lift;

    public string $name;

    #[Cast('float')]
    public float $price;

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
