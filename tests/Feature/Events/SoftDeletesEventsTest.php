<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use Tests\Datasets\Car;

it('force deletion event handlers get called', function () {
    $car = Car::castAndCreate(['name' => 'yellow card']);

    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onForceDeleting has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onForceDeleted has been called'));

    $car->forceDelete();

    $this->assertTrue(true);
});

it('restore event handlers get called', function () {
    $car = Car::castAndCreate(['name' => 'yellow card']);

    $car->delete();

    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onRestoring has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onRestored has been called'));

    $car->restore();

    $this->assertTrue(true);
});
