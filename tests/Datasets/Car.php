<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use WendellAdriel\Lift\Attributes\Events\ForceDeleted;
use WendellAdriel\Lift\Attributes\Events\ForceDeleting;
use WendellAdriel\Lift\Attributes\Events\Listener;
use WendellAdriel\Lift\Attributes\Events\Restored;
use WendellAdriel\Lift\Attributes\Events\Restoring;
use WendellAdriel\Lift\Lift;

class Car extends Model
{
    use Lift;
    use SoftDeletes;

    public int $id;

    public string $name;

    #[Listener]
    public function onForceDeleting(Car $car): void
    {
        Cache::set('onForceDeleting',true);
    }

    #[Listener]
    public function onForceDeleted(Car $car): void
    {
        Cache::set('onForceDeleted',true);
    }

    #[Listener]
    public function onRestoring(Car $car): void
    {
        Cache::set('onRestoring',true);
    }

    #[Listener]
    public function onRestored(Car $car): void
    {
        Cache::set('onRestored',true);
    }
}
