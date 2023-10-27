<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Tests\Support\Events\ProductObserver;
use Tests\Support\Events\ProductSaved;
use Tests\Support\Events\ProductSaving;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Events\Dispatches;
use WendellAdriel\Lift\Attributes\Events\Listener;
use WendellAdriel\Lift\Attributes\Events\Observer;
use WendellAdriel\Lift\Attributes\IgnoreProperties;
use WendellAdriel\Lift\Lift;

#[Observer(ProductObserver::class)]
#[Dispatches(ProductSaving::class)]
#[Dispatches(ProductSaved::class, 'saved')]
#[IgnoreProperties('hash', 'hash2')]
#[IgnoreProperties('hash3')]
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

    #[Cast('array')]
    public ?array $json_column;

    public string $hash;

    public string $hash2;

    public string $hash3;

    protected $fillable = [
        'name',
        'price',
        'random_number',
        'expires_at',
        'json_column',
    ];

    #[Listener]
    public function blabla(Product $product): void
    {
        throw new Exception("this function musn't be called");
    }

    #[Listener]
    public function onRetrieved(Product $product): void
    {
        Cache::set('onRetrieved', true);
    }

    #[Listener('creating')]
    public function onWhatever(Product $product): void
    {
        Cache::set('onCreating', true);
    }

    #[Listener]
    public function onCreated(Product $product): void
    {
        Cache::set('onCreated', true);
    }

    #[Listener]
    public function onUpdating(Product $product): void
    {
        Cache::set('onUpdating', true);
    }

    #[Listener]
    public function onUpdated(Product $product): void
    {
        Cache::set('onUpdated', true);
    }

    #[Listener]
    public function onSaving(Product $product): void
    {
        Cache::set('onSaving', true);
    }

    #[Listener]
    public function onSaved(Product $product): void
    {
        Cache::set('onSaved', true);
    }

    #[Listener]
    public function onDeleting(Product $product): void
    {
        Cache::set('onDeleting', true);
    }

    #[Listener(queue: true)]
    public function onDeleted(Product $product): void
    {
        Cache::set('onDeleted', true);
    }

    #[Listener('replicating')]
    public function onReplicating(Product $product): void
    {
        Cache::set('onReplicating', true);
    }
}
