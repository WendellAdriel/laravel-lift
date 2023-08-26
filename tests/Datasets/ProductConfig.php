<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Lift;

#[DB(table: 'products')]
class ProductConfig extends Model
{
    use Lift;

    #[Config(fillable: true, rules: ['required', 'string'], messages: ['required' => 'The PRODUCT NAME field cannot be empty.'])]
    public string $name;

    #[Config(fillable: true, cast: 'float', rules: ['required', 'numeric'])]
    public float $price;

    #[Config(fillable: true, cast: 'int', hidden: true, rules: ['required', 'integer'])]
    public int $random_number;

    #[Config(fillable: true, cast: 'immutable_datetime', rules: ['required', 'date_format:Y-m-d H:i:s'])]
    public CarbonImmutable $expires_at;
}
