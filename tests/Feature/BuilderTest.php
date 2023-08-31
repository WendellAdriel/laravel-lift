<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Builder;
use WendellAdriel\Lift\Tests\Datasets\Category;
use WendellAdriel\Lift\Tests\Datasets\CategoryQueryBuilder;
use WendellAdriel\Lift\Tests\Datasets\CategoryWithoutCustomQueryBuilder;

it('load custom query builder', function () {
    $categoryQuery = (new Category())->newModelQuery();
    expect($categoryQuery)
        ->toBeInstanceOf(Builder::class)
        ->toBeInstanceOf(CategoryQueryBuilder::class);
});

it('use default query builder', function () {
    $categoryQuery = (new CategoryWithoutCustomQueryBuilder())->newModelQuery();
    expect($categoryQuery)
        ->toBeInstanceOf(Builder::class)
        ->not
        ->toBeInstanceOf(CategoryQueryBuilder::class);
});
