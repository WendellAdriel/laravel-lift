<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Events\Created;
use WendellAdriel\Lift\Attributes\Events\Creating;
use WendellAdriel\Lift\Attributes\Events\Deleted;
use WendellAdriel\Lift\Attributes\Events\Deleting;
use WendellAdriel\Lift\Attributes\Events\Replicating;
use WendellAdriel\Lift\Attributes\Events\Retrieved;
use WendellAdriel\Lift\Attributes\Events\Saved;
use WendellAdriel\Lift\Attributes\Events\Saving;
use WendellAdriel\Lift\Attributes\Events\Updated;
use WendellAdriel\Lift\Attributes\Events\Updating;
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

    #[Cast('array')]
    public ?array $json_column;

    protected $fillable = [
        'name',
        'price',
        'random_number',
        'expires_at',
        'json_column',
    ];

    #[Retrieved]
    public function onRetrieved(Product $product): void
    {
        Log::info('onRetrieved has been called');
    }

    #[Creating]
    public function onCreating(Product $product): void
    {
        Log::info('onCreating has been called');
    }

    #[Created]
    public function onCreated(Product $product): void
    {
        Log::info('onCreated has been called');
    }

    #[Updating]
    public function onUpdating(Product $product): void
    {
        Log::info('onUpdating has been called');
    }

    #[Updated]
    public function onUpdated(Product $product): void
    {
        Log::info('onUpdated has been called');
    }

    #[Saving]
    public function onSaving(Product $product): void
    {
        Log::info('onSaving has been called');
    }

    #[Saved]
    public function onSaved(Product $product): void
    {
        Log::info('onSaved has been called');
    }

    #[Deleting]
    public function onDeleting(Product $product): void
    {
        Log::info('onDeleting has been called');
    }

    #[Deleted]
    public function onDeleted(Product $product): void
    {
        Log::info('onDeleted has been called');
    }

    #[Replicating]
    public function onReplicating(Product $product): void
    {
        Log::info('onReplicating has been called');
    }
}
