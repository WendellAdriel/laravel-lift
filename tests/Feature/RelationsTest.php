<?php

declare(strict_types=1);

use WendellAdriel\Lift\Tests\Datasets\Country;
use WendellAdriel\Lift\Tests\Datasets\Phone;
use WendellAdriel\Lift\Tests\Datasets\Post;
use WendellAdriel\Lift\Tests\Datasets\Role;
use WendellAdriel\Lift\Tests\Datasets\User;

it('loads BelongsTo relation', function () {
    $user = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    $post = Post::create([
        'title' => fake()->sentence,
        'content' => fake()->paragraph,
    ]);

    $post->user()->associate($user);
    $post->save();
    expect($post->user->id)->toBe($user->id);

    $post = Post::query()->find($post->id);
    expect($post->user->id)->toBe($user->id);

    $postWithoutUser = Post::create([
        'title' => fake()->sentence,
        'content' => fake()->paragraph,
    ]);

    expect($postWithoutUser->user)->toBeNull();

    $postWithoutUser = Post::query()->find($postWithoutUser->id);
    expect($postWithoutUser->user)->toBeNull();
});

it('loads BelongsToMany relation', function () {
    $user = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    $role = Role::create();
    $user->roles()->attach($role);

    expect($user->roles)->toHaveCount(1)
        ->and($user->roles->first()->id)->toBe($role->id)
        ->and($role->users)->toHaveCount(1)
        ->and($role->users->first()->id)->toBe($user->id);

    $user = User::query()->find($user->id);
    expect($user->roles)->toHaveCount(1)
        ->and($user->roles->first()->id)->toBe($role->id);

    $role = Role::query()->find($role->id);
    expect($role->users)->toHaveCount(1)
        ->and($role->users->first()->id)->toBe($user->id);

    $userWithoutRoles = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);
    expect($userWithoutRoles->roles)->toHaveCount(0);

    $userWithoutRoles = User::query()->find($userWithoutRoles->id);
    expect($userWithoutRoles->roles)->toHaveCount(0);

    $roleWithoutUsers = Role::create();
    expect($roleWithoutUsers->users)->toHaveCount(0);

    $roleWithoutUsers = Role::query()->find($roleWithoutUsers->id);
    expect($roleWithoutUsers->users)->toHaveCount(0);
});

it('loads HasMany relation', function () {
    $user = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    $post = Post::create([
        'title' => fake()->sentence,
        'content' => fake()->paragraph,
    ]);
    $user->posts()->save($post);

    expect($user->posts)->toHaveCount(1)
        ->and($user->posts->first()->id)->toBe($post->id)
        ->and($post->user->id)->toBe($user->id);

    $user = User::query()->find($user->id);
    expect($user->posts)->toHaveCount(1)
        ->and($user->posts->first()->id)->toBe($post->id);

    $post = Post::query()->find($post->id);
    expect($post->user->id)->toBe($user->id);

    $userWithoutPosts = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    expect($userWithoutPosts->posts)->toHaveCount(0);
});

it('loads HasManyThrough relation', function () {
    $country = Country::create();

    $user = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    $post = Post::create([
        'title' => fake()->sentence,
        'content' => fake()->paragraph,
    ]);

    $country->users()->save($user);
    $user->posts()->save($post);

    expect($country->posts)->toHaveCount(1)
        ->and($country->posts->first()->id)->toBe($post->id);

    $country = Country::query()->find($country->id);
    expect($country->posts)->toHaveCount(1)
        ->and($country->posts->first()->id)->toBe($post->id);

    $countryWithoutPosts = Country::create();
    expect($countryWithoutPosts->posts)->toHaveCount(0);
});

it('loads HasOne relation', function () {
    $user = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    $phone = Phone::create();
    $user->phone()->save($phone);

    expect($user->phone->id)->toBe($phone->id)
        ->and($phone->user->id)->toBe($user->id);

    $user = User::query()->find($user->id);
    expect($user->phone->id)->toBe($phone->id);

    $phone = Phone::query()->find($phone->id);
    expect($phone->user->id)->toBe($user->id);

    $userWithoutPhone = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    expect($userWithoutPhone->phone)->toBeNull();
});

it('loads HasOneThrough relation', function () {

})->todo();

it('loads MorphedByMany relation', function () {

})->todo();

it('loads MorphMany relation', function () {

})->todo();

it('loads MorphOne relation', function () {

})->todo();

it('loads MorphTo relation', function () {

})->todo();

it('loads MorphToMany relation', function () {

})->todo();
