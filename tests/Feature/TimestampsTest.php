<?php

declare(strict_types=1);

use Tests\Datasets\PostTimestamps;

it('should set timestamps on create', function () {
    PostTimestamps::create([
        'title' => 'Test',
        'content' => 'Test',
    ]);

    $post = PostTimestamps::first();
    expect($post->created_at)->not->toBeNull();
    expect($post->updated_at)->not->toBeNull();
});

it('should set timestamps on update', function () {
    $post = PostTimestamps::create([
        'title' => 'Test',
        'content' => 'Test',
    ]);
    sleep(2); // Wait 2 seconds to update
    $post->title = 'Test 2';
    $post->save();
    $post = $post->fresh();

    expect($post->created_at)->not->toBeNull();
    expect($post->updated_at)->not->toBeNull();
    expect($post->created_at)->not->toEqual($post->updated_at);
    expect($post->updated_at)->toBeGreaterThan($post->created_at);
    expect($post->title)->toBe('Test 2');
});
