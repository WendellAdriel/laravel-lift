<?php

declare(strict_types=1);

use Tests\Datasets\Product;
use Tests\Datasets\User;

it('ignores no additional properties if not set', function () {
    $rMethod = new ReflectionMethod(User::class, 'ignoredProperties');
    expect($rMethod->invoke(null))
        ->toMatchArray([
            'incrementing',
            'preventsLazyLoading',
            'exists',
            'wasRecentlyCreated',
            'snakeAttributes',
            'encrypter',
            'manyMethods',
            'timestamps',
            'usesUniqueIds',
        ]);
});

it('ignores additional properties', function () {
    $rMethod = new ReflectionMethod(Product::class, 'ignoredProperties');
    expect($rMethod->invoke(null))
        ->toMatchArray([
            'incrementing',
            'preventsLazyLoading',
            'exists',
            'wasRecentlyCreated',
            'snakeAttributes',
            'encrypter',
            'manyMethods',
            'timestamps',
            'usesUniqueIds',
            'hash',
            'hash2',
            'hash3',
        ]);
});
