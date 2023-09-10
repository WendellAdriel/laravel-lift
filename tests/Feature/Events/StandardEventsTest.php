<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Log;
use Tests\Datasets\Product;
use Tests\Datasets\ProductConfig;

it('onRetrieved method gets called', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onRetrieved has been called'));
    $product->refresh();
    $this->assertTrue(true);
});

it('cause events on a model without event listeners', function () {
    Log::shouldReceive('info')->never()->withArgs(fn ($message) => str_contains($message, 'onCreating has been called'));
    Log::shouldReceive('info')->never()->withArgs(fn ($message) => str_contains($message, 'onCreated has been called'));
    Log::shouldReceive('info')->never()->withArgs(fn ($message) => str_contains($message, 'onSaving has been called'));
    Log::shouldReceive('info')->never()->withArgs(fn ($message) => str_contains($message, 'onSaved has been called'));
    $product = ProductConfig::create([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
    ]);
});

it('creation event handlers get called', function () {
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onCreating has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onCreated has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onSaving has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onSaved has been called'));

    Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    $this->assertTrue(true);
});

it('update event handlers get called', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onUpdating has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onSaving has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onSaved has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onUpdated has been called'));

    $product->update(['name' => 'Product11']);
    $this->assertTrue(true);
});
it('deletion event handlers get called', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onDeleting has been called'));
    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onDeleted has been called'));

    $product->delete();
    $this->assertTrue(true);
});

it('onReplicating method gets called', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    Log::shouldReceive('info')->withArgs(fn ($message) => str_contains($message, 'onReplicating has been called'));

    $product->replicate();
    $this->assertTrue(true);
});
