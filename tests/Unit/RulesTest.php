<?php

declare(strict_types=1);

use Illuminate\Validation\ValidationException;
use WendellAdriel\Lift\Tests\Datasets\User;

it('throws validation error if model data is invalid', function () {
    User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
    ]);
})->throws(ValidationException::class);

it('does not throw validation error if model data is valid', function () {
    $user = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3t@!!!',
    ]);

    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'name' => $user->name,
        'email' => $user->email,
    ]);
});
