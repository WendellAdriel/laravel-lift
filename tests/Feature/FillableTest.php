<?php

declare(strict_types=1);

use WendellAdriel\Lift\Tests\Datasets\ProductFillable;
use WendellAdriel\Lift\Tests\Datasets\UserGuarded;

it('set fillable properties', function () {
    $product = ProductFillable::create([
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

it('can update not fillable properties when setting them individually', function () {
    $user = UserGuarded::make([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
    ]);

    $user->password = 's3Cr3T@!!!';
    $user->save();

    $this->assertDatabaseCount(UserGuarded::class, 1);
    $this->assertDatabaseHas(UserGuarded::class, [
        'name' => $user->name,
        'email' => $user->email,
        'password' => 's3Cr3T@!!!',
    ]);
});
