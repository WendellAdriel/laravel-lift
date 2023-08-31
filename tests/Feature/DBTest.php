<?php

declare(strict_types=1);

use Tests\Datasets\User;
use Tests\Datasets\UserCustomDB;

it('gets the default values for database configurations if not set', function () {
    $user = new User();

    expect($user->getConnection()->getName())->toBe('testing')
        ->and($user->getTable())->toBe('users')
        ->and($user->timestamps)->toBeTrue();
});

it('gets custom database configurations', function () {
    $user = new UserCustomDB();

    expect($user->getConnection()->getName())->toBe('mysql')
        ->and($user->getTable())->toBe('users_custom_db')
        ->and($user->timestamps)->toBeFalse();
});
