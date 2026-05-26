<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use Tests\Datasets\Article;
use Tests\Datasets\Enums\ArticleStatusEnum;
use Tests\Datasets\Product;
use Tests\Datasets\ProductColumn;

it('casts values when creating model', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    expect($product->name)->toBe('Product 1')
        ->and($product->price)->toBe(10.99)
        ->and($product->random_number)->toBe(123)
        ->and($product->expires_at)->toBeInstanceOf(CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2023-12-31 23:59:59')
        ->and($product->json_column)->toBe(['foo' => 'bar']);

    $this->assertDatabaseHas(Product::class, [
        'name' => 'Product 1',
        'price' => 10.99,
        'random_number' => 123,
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => '{"foo":"bar"}',
    ]);
});

it('casts values when updating model with updateAndCast', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
    ]);

    $product->castAndUpdate([
        'name' => 'Product 2',
        'price' => '20.99',
        'random_number' => '456',
        'expires_at' => '2024-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    expect($product->name)->toBe('Product 2')
        ->and($product->price)->toBe(20.99)
        ->and($product->random_number)->toBe(456)
        ->and($product->expires_at)->toBeInstanceOf(CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2024-12-31 23:59:59')
        ->and($product->json_column)->toBe(['foo' => 'bar']);

    $this->assertDatabaseHas(Product::class, [
        'name' => 'Product 2',
        'price' => 20.99,
        'random_number' => 456,
        'expires_at' => '2024-12-31 23:59:59',
        'json_column' => '{"foo":"bar"}',
    ]);
});

it('casts value when updating model with fillAndCast', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
    ]);

    $product->castAndFill([
        'name' => 'Product 2',
        'price' => '20.99',
        'random_number' => '456',
        'expires_at' => '2024-12-31 23:59:59',
        'json_column' => '{"foo":"bar"}',
    ]);
    $product->save();

    expect($product->name)->toBe('Product 2')
        ->and($product->price)->toBe(20.99)
        ->and($product->random_number)->toBe(456)
        ->and($product->expires_at)->toBeInstanceOf(CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2024-12-31 23:59:59')
        ->and($product->json_column)->toBe(['foo' => 'bar']);

    $this->assertDatabaseHas(Product::class, [
        'name' => 'Product 2',
        'price' => 20.99,
        'random_number' => 456,
        'expires_at' => '2024-12-31 23:59:59',
        'json_column' => '{"foo":"bar"}',
    ]);
});

it('casts value when updating model with setAndCast', function () {
    $product = Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
    ]);

    $product->castAndSet('json_column', '{"foo": "bar"}');
    $product->save();

    expect($product->name)->toBe('Product 1')
        ->and($product->price)->toBe(10.99)
        ->and($product->random_number)->toBe(123)
        ->and($product->expires_at)->toBeInstanceOf(CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2023-12-31 23:59:59')
        ->and($product->json_column)->toBe(['foo' => 'bar']);

    $this->assertDatabaseHas(Product::class, [
        'name' => 'Product 1',
        'price' => 10.99,
        'random_number' => 123,
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => '{"foo":"bar"}',
    ]);
});

it('casts values when retrieving model', function () {
    Product::castAndCreate([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => '{"foo": "bar"}',
    ]);
    $product = Product::query()->first();

    expect($product->name)->toBe('Product 1')
        ->and($product->price)->toBe(10.99)
        ->and($product->random_number)->toBe(123)
        ->and($product->expires_at)->toBeInstanceOf(CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2023-12-31 23:59:59')
        ->and($product->json_column)->toBe(['foo' => 'bar']);
});

it('casts values when retrieving model with custom columns', function () {
    ProductColumn::create([
        'name' => 'Product 1',
        'price' => '10.99',
        'random_number' => '123',
        'expires_at' => '2023-12-31 23:59:59',
        'json_column' => ['foo' => 'bar'],
    ]);

    $product = Product::query()->first();

    expect($product->name)->toBe('Product 1')
        ->and($product->price)->toBe(10.99)
        ->and($product->random_number)->toBe(123)
        ->and($product->expires_at)->toBeInstanceOf(CarbonImmutable::class)
        ->and($product->expires_at->format('Y-m-d H:i:s'))->toBe('2023-12-31 23:59:59')
        ->and($product->json_column)->toBe(['foo' => 'bar']);
});

it('casts values when creating model with enum cast', closure: function () {
    $article = Article::castAndCreate([
        'status' => ArticleStatusEnum::ARCHIVED,
    ]);

    expect($article->status)->toBe(ArticleStatusEnum::ARCHIVED);

    $this->assertDatabaseHas(Article::class, [
        'status' => ArticleStatusEnum::ARCHIVED,
        'id' => $article->id,
    ]);
    $this->assertSame(ArticleStatusEnum::ARCHIVED, $article->status);
});
