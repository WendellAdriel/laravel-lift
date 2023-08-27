<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use WendellAdriel\Lift\Tests\Datasets\ProductWatch;

final class RandomNumberChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ProductWatch $product,
    ) {
    }
}
