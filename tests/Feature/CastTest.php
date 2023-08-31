<?php

declare(strict_types=1);

use Tests\Datasets\Product;

it('casts values when creating model', function () {
    $product = Product::create([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
    ]);

    expect($product->name)->toBe('Product 1')
        ->and($product->price)->toBe(10.99)
        ->and($product->random_number)->toBe(123)
        ->and($product->expires_at)->toBeInstanceOf(Carbon\CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2023-12-31 23:59:59');
});

it('casts values when updating model', function () {
    $product = Product::create([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
    ]);

    $product->update([
        'name' => 'Product 2',
        'price' => '20.99',
        'random_number' => '456',
        'expires_at' => '2024-12-31 23:59:59',
    ]);

    expect($product->name)->toBe('Product 2')
        ->and($product->price)->toBe(20.99)
        ->and($product->random_number)->toBe(456)
        ->and($product->expires_at)->toBeInstanceOf(Carbon\CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2024-12-31 23:59:59');
});

it('casts values when retrieving model', function () {
    Product::create([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
    ]);
    $product = Product::query()->first();

    expect($product->name)->toBe('Product 1')
        ->and($product->price)->toBe(10.99)
        ->and($product->random_number)->toBe(123)
        ->and($product->expires_at)->toBeInstanceOf(Carbon\CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2023-12-31 23:59:59');
});
