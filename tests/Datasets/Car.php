<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use WendellAdriel\Lift\Attributes\Events\ForceDeleted;
use WendellAdriel\Lift\Attributes\Events\ForceDeleting;
use WendellAdriel\Lift\Attributes\Events\Restored;
use WendellAdriel\Lift\Attributes\Events\Restoring;
use WendellAdriel\Lift\Lift;

class Car extends Model
{
    use Lift;
    use SoftDeletes;

    public int $id;

    public string $name;

    #[ForceDeleting]
    public function onForceDeleting(Car $car): void
    {
        Log::info('onForceDeleting has been called');
    }

    #[ForceDeleted]
    public function onForceDeleted(Car $car): void
    {
        Log::info('onForceDeleted has been called');
    }

    #[Restoring]
    public function onRestoring(Car $car): void
    {
        Log::info('onRestoring has been called');
    }

    #[Restored]
    public function onRestored(Car $car): void
    {
        Log::info('onRestored has been called');
    }
}
