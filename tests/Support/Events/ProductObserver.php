<?php

namespace Tests\Support\Events;

use Illuminate\Support\Facades\Cache;
use Tests\Datasets\Product;

class ProductObserver
{
    public function created(Product $product): void
    {
        Cache::set('created_observer', true);
    }
}
