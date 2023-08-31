<?php

declare(strict_types=1);

use Tests\Datasets\ProductImmutable;
use WendellAdriel\Lift\Exceptions\ImmutablePropertyException;

it('returns the list of immutable properties for the model', function () {
    expect(ProductImmutable::immutableProperties())->toBe([
        'name',
        'random_number',
    ]);
});

describe('throws exception when trying to update immutable property', function () {
    it('setting individual properties', function () {
        $product = ProductImmutable::create([
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123456,
            'expires_at' => '2023-12-31 23:59:59',
        ]);

        $product->name = 'Product 2';
        $product->price = 20.99;
        $product->random_number = 654321;
        $product->save();

        $this->assertDatabaseCount(ProductImmutable::class, 1);
        $this->assertDatabaseHas(ProductImmutable::class, [
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123456,
            'expires_at' => '2023-12-31 23:59:59',
        ]);
    })->throws(ImmutablePropertyException::class);

    it('with fill + save methods', function () {
        $product = ProductImmutable::create([
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123456,
            'expires_at' => '2023-12-31 23:59:59',
        ]);

        $product->fill([
            'name' => 'Product 2',
            'price' => 20.99,
            'random_number' => 654321,
        ])->save();

        $this->assertDatabaseCount(ProductImmutable::class, 1);
        $this->assertDatabaseHas(ProductImmutable::class, [
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123456,
            'expires_at' => '2023-12-31 23:59:59',
        ]);
    })->throws(ImmutablePropertyException::class);

    it('with update method', function () {
        $product = ProductImmutable::create([
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123456,
            'expires_at' => '2023-12-31 23:59:59',
        ]);

        $product->update([
            'name' => 'Product 2',
            'price' => 20.99,
            'random_number' => 654321,
        ]);

        $this->assertDatabaseCount(ProductImmutable::class, 1);
        $this->assertDatabaseHas(ProductImmutable::class, [
            'name' => 'Product 1',
            'price' => 10.99,
            'random_number' => 123456,
            'expires_at' => '2023-12-31 23:59:59',
        ]);
    })->throws(ImmutablePropertyException::class);
});
