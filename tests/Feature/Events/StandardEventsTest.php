<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\Datasets\Product;
use Tests\Datasets\ProductConfig;
use Tests\Support\Events\ProductSaved;
use Tests\Support\Events\ProductSaving;

it('onRetrieved method gets called', function () {

    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);
    $product->refresh();
    $this->assertTrue(Cache::has('onRetrieved'));
});

it('cause events on a model without event listeners', function () {
    $product = ProductConfig::create([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
    ]);

    $this->assertTrue(! Cache::has('onCreating'));
    $this->assertTrue(! Cache::has('onCreated'));
    $this->assertTrue(! Cache::has('created_observer'));
    $this->assertTrue(! Cache::has('onSaving'));
    $this->assertTrue(! Cache::has('onSaved'));
});

it('creation event handlers get called', function () {
    Event::fake([
        ProductSaving::class,
        ProductSaved::class,
    ]);
    Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    $this->assertTrue(Cache::has('onCreating'));
    $this->assertTrue(Cache::has('onCreated'));
    $this->assertTrue(Cache::has('created_observer'));
    $this->assertTrue(Cache::has('onSaving'));
    $this->assertTrue(Cache::has('onSaved'));
    Event::assertDispatched(ProductSaved::class);
    Event::assertDispatched(ProductSaving::class);
});

it('update event handlers get called', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    $product->update(['name' => 'Product11']);

    $this->assertTrue(Cache::has('onUpdating'));
    $this->assertTrue(Cache::has('onUpdated'));
    $this->assertTrue(Cache::has('onSaving'));
    $this->assertTrue(Cache::has('onSaved'));
});
it('deletion event handlers get called', function () {
    Queue::fake();
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    $product->delete();

    $this->assertTrue(Cache::has('onDeleting'));
    Queue::assertClosurePushed();
});

it('onReplicating method gets called', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    $product->replicate();
    $this->assertTrue(Cache::has('onReplicating'));
});
