<?php

declare(strict_types=1);

use WendellAdriel\Lift\Tests\Datasets\PostBelongsTo;
use WendellAdriel\Lift\Tests\Datasets\User;

it('loads BelongsTo relation', function () {
    $user = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    $post = PostBelongsTo::create([
        'title' => fake()->sentence,
        'content' => fake()->paragraph,
    ]);

    $post->user()->associate($user);
    $post->save();
    expect($post->user->id)->toBe($user->id);

    $post = PostBelongsTo::query()->find($post->id);
    expect($post->user->id)->toBe($user->id);

    $postWithoutUser = PostBelongsTo::create([
        'title' => fake()->sentence,
        'content' => fake()->paragraph,
    ]);

    expect($postWithoutUser->user)->toBeNull();

    $postWithoutUser = PostBelongsTo::query()->find($postWithoutUser->id);
    expect($postWithoutUser->user)->toBeNull();
});
