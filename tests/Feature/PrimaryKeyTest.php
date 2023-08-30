<?php

declare(strict_types=1);

use WendellAdriel\Lift\Tests\Datasets\Movie;
use WendellAdriel\Lift\Tests\Datasets\User;
use WendellAdriel\Lift\Tests\Datasets\UserCustom;
use WendellAdriel\Lift\Tests\Datasets\UserUuid;

it('returns the default values for primary key when not set', function () {
    $user = new User();

    expect($user->getKeyName())->toBe('id')
        ->and($user->getKeyType())->toBe('int')
        ->and($user->getIncrementing())->toBe(true);
});

describe('custom primary key with custom incrementing value', function () {
    it('returns custom primary key values', function () {
        $user = new UserCustom();

        expect($user->getKeyName())->toBe('id')
            ->and($user->getKeyType())->toBe('int')
            ->and($user->getIncrementing())->toBe(false);
    });

    it('creates model with custom primary key', function () {
        $user = UserCustom::create([
            'id' => 10,
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $this->assertDatabaseCount(UserCustom::class, 1);
        $this->assertDatabaseHas(UserCustom::class, [
            'id' => 10,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    });

    it('updates model with custom primary key', function () {
        $user = UserCustom::create([
            'id' => 10,
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->update([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $this->assertDatabaseCount(UserCustom::class, 1);
        $this->assertDatabaseHas(UserCustom::class, [
            'id' => 10,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
    });

    it('retrieves user with custom primary key', function () {
        UserCustom::create([
            'id' => 5,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3t@!!!',
        ]);
        $user = UserCustom::query()->find(5);

        expect($user->id)->toBe(5)
            ->and($user->name)->toBe('John Doe')
            ->and($user->email)->toBe('john.doe@example.com');
    });
});

describe('custom primary key with custom type and incrementing value', function () {
    it('returns custom primary key values', function () {
        $user = new UserUuid();

        expect($user->getKeyName())->toBe('uuid')
            ->and($user->getKeyType())->toBe('string')
            ->and($user->getIncrementing())->toBe(false);
    });

    it('creates model with custom primary key', function () {
        $user = UserUuid::create([
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $this->assertDatabaseCount(UserUuid::class, 1);
        $this->assertDatabaseHas(UserUuid::class, [
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => $user->name,
            'email' => $user->email,
        ]);
    });

    it('updates model with custom primary key', function () {
        $user = UserUuid::create([
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->update([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $this->assertDatabaseCount(UserUuid::class, 1);
        $this->assertDatabaseHas(UserUuid::class, [
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
    });

    it('retrieves user with custom primary key', function () {
        UserUuid::create([
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3t@!!!',
        ]);
        $user = UserUuid::query()->find('123e4567-e89b-12d3-a456-426614174000');

        expect($user->uuid)->toBe('123e4567-e89b-12d3-a456-426614174000')
            ->and($user->name)->toBe('John Doe')
            ->and($user->email)->toBe('john.doe@example.com');
    });
});

describe('custom primary key with custom column name', function () {
    it('returns custom primary key values', function () {
        $movie = new Movie();

        expect($movie->getKeyName())->toBe('movie_id')
            ->and($movie->getKeyType())->toBe('string')
            ->and($movie->getIncrementing())->toBe(false);
    });

    it('creates model with custom primary key', function () {
        $movie = Movie::create([
            'id' => '123e4567-e89b-12d3-a456-426614174000',
        ]);

        $this->assertDatabaseCount(Movie::class, 1);
        $this->assertDatabaseHas(Movie::class, [
            'movie_id' => '123e4567-e89b-12d3-a456-426614174000',
        ]);
    });

    it('updates model with custom primary key', function () {
        $movie = Movie::create([
            'id' => '123e4567-e89b-12d3-a456-426614174000',
        ]);

        $movie->update([
            'id' => '123e4567-e89b-12d3-a456-426614174001',
        ]);

        $this->assertDatabaseCount(Movie::class, 1);
        $this->assertDatabaseHas(Movie::class, [
            'movie_id' => '123e4567-e89b-12d3-a456-426614174001',
        ]);
    });

    it('retrieves user with custom primary key', function () {
        Movie::create([
            'id' => '123e4567-e89b-12d3-a456-426614174000',
        ]);
        $movie = Movie::query()->find('123e4567-e89b-12d3-a456-426614174000');

        expect($movie->id)->toBe('123e4567-e89b-12d3-a456-426614174000');
    });
});
