<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Tests\Datasets\Car;

it('force deletion event handlers get called', function () {
    $car = Car::castAndCreate(['name' => 'yellow card']);

    $car->forceDelete();

    $this->assertTrue(Cache::has('onForceDeleting'));
    $this->assertTrue(Cache::has('onForceDeleted'));

    $this->assertTrue(true);
});

it('restore event handlers get called', function () {
    $car = Car::castAndCreate(['name' => 'yellow card']);

    $car->delete();

    $car->restore();

    $this->assertTrue(Cache::has('onRestoring'));
    $this->assertTrue(Cache::has('onRestored'));

    $this->assertTrue(true);
});
