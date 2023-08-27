<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Tests\Datasets\PriceChangedEvent;
use Tests\Datasets\RandomNumberChangedEvent;
use WendellAdriel\Lift\Tests\Datasets\ProductWatch;

it('returns the list of watched properties for the model', function () {
    expect(ProductWatch::watchedProperties())->toBe([
        'price' => PriceChangedEvent::class,
        'random_number' => RandomNumberChangedEvent::class,
    ]);
});

describe('dispatches event when watched property is updated', function () {
    it('setting individual properties', function () {
        Event::fake([
            PriceChangedEvent::class,
            RandomNumberChangedEvent::class,
        ]);

        $product = ProductWatch::create([
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123,
            'expires_at' => '2023-12-31 23:59:59',
        ]);

        $product->price = 20.99;
        $product->random_number = 456;
        $product->save();

        Event::assertDispatched(PriceChangedEvent::class, fn ($event) => $event->product->id === $product->id);

        Event::assertDispatched(RandomNumberChangedEvent::class, fn ($event) => $event->product->id === $product->id);
    });

    it('with fill + save methods', function () {
        Event::fake([
            PriceChangedEvent::class,
            RandomNumberChangedEvent::class,
        ]);

        $product = ProductWatch::create([
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123,
            'expires_at' => '2023-12-31 23:59:59',
        ]);

        $product->fill([
            'price' => 20.99,
            'random_number' => 456,
        ])->save();

        Event::assertDispatched(PriceChangedEvent::class, fn ($event) => $event->product->id === $product->id);

        Event::assertDispatched(RandomNumberChangedEvent::class, fn ($event) => $event->product->id === $product->id);
    });

    it('with update method', function () {
        Event::fake([
            PriceChangedEvent::class,
            RandomNumberChangedEvent::class,
        ]);

        $product = ProductWatch::create([
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123,
            'expires_at' => '2023-12-31 23:59:59',
        ]);

        $product->update([
            'price' => 20.99,
            'random_number' => 456,
        ]);

        Event::assertDispatched(PriceChangedEvent::class, fn ($event) => $event->product->id === $product->id);

        Event::assertDispatched(RandomNumberChangedEvent::class, fn ($event) => $event->product->id === $product->id);
    });
});
