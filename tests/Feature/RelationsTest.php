<?php

declare(strict_types=1);

use WendellAdriel\Lift\Tests\Datasets\Book;
use WendellAdriel\Lift\Tests\Datasets\BookCase;
use WendellAdriel\Lift\Tests\Datasets\Computer;
use WendellAdriel\Lift\Tests\Datasets\Country;
use WendellAdriel\Lift\Tests\Datasets\Image;
use WendellAdriel\Lift\Tests\Datasets\Manufacturer;
use WendellAdriel\Lift\Tests\Datasets\Phone;
use WendellAdriel\Lift\Tests\Datasets\Post;
use WendellAdriel\Lift\Tests\Datasets\Role;
use WendellAdriel\Lift\Tests\Datasets\Seller;
use WendellAdriel\Lift\Tests\Datasets\Tag;
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
    expect($post->user->id)->toBe($user->id)
        ->and($post->user_id)->toBe($user->id);

    $post = Post::query()->find($post->id);
    expect($post->user->id)->toBe($user->id)
        ->and($post->user_id)->toBe($user->id);

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
        ->and($post->user->id)->toBe($user->id)
        ->and($post->user_id)->toBe($user->id);

    $user = User::query()->find($user->id);
    expect($user->posts)->toHaveCount(1)
        ->and($user->posts->first()->id)->toBe($post->id);

    $post = Post::query()->find($post->id);
    expect($post->user->id)->toBe($user->id)
        ->and($post->user_id)->toBe($user->id);

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
        ->and($phone->user->id)->toBe($user->id)
        ->and($phone->user_id)->toBe($user->id);

    $user = User::query()->find($user->id);
    expect($user->phone->id)->toBe($phone->id);

    $phone = Phone::query()->find($phone->id);
    expect($phone->user->id)->toBe($user->id)
        ->and($phone->user_id)->toBe($user->id);

    $userWithoutPhone = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);

    expect($userWithoutPhone->phone)->toBeNull();
});

it('loads HasOneThrough relation', function () {
    $seller = Seller::create();
    $computer = Computer::create();
    $manufacturer = Manufacturer::create();

    $seller->computer()->save($computer);
    $computer->manufacturer()->save($manufacturer);

    expect($seller->manufacturer->id)->toBe($manufacturer->id)
        ->and($seller->computer->id)->toBe($computer->id)
        ->and($computer->manufacturer->id)->toBe($manufacturer->id);

    $seller = Seller::query()->find($seller->id);
    expect($seller->manufacturer->id)->toBe($manufacturer->id);

    $sellerWithoutManufacturer = Seller::create();
    expect($sellerWithoutManufacturer->manufacturer)->toBeNull();
});

it('loads MorphMany/MorphTo relations', function () {
    $post = Post::create([
        'title' => fake()->sentence,
        'content' => fake()->paragraph,
    ]);
    $image = $post->images()->save(new Image());

    expect($post->images)->toHaveCount(1)
        ->and($post->images->first()->id)->toBe($image->id)
        ->and($image->imageable->id)->toBe($post->id);

    $post = Post::query()->find($post->id);
    expect($post->images)->toHaveCount(1)
        ->and($post->images->first()->id)->toBe($image->id);

    $image = Image::query()->find($image->id);
    expect($image->imageable->id)->toBe($post->id);
});

it('loads MorphOne relation', function () {
    $user = User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'password' => 's3Cr3T@!!!',
    ]);
    $image = $user->image()->save(new Image());

    expect($user->image->id)->toBe($image->id)
        ->and($image->imageable->id)->toBe($user->id);

    $user = User::query()->find($user->id);
    expect($user->image->id)->toBe($image->id);

    $image = Image::query()->find($image->id);
    expect($image->imageable->id)->toBe($user->id);
});

it('loads MorphToMany/MorphedByMany relations', function () {
    $post = Post::create([
        'title' => fake()->sentence,
        'content' => fake()->paragraph,
    ]);
    $tag = Tag::create();

    $post->tags()->attach($tag);

    expect($post->tags)->toHaveCount(1)
        ->and($post->tags->first()->id)->toBe($tag->id)
        ->and($tag->posts)->toHaveCount(1)
        ->and($tag->posts->first()->id)->toBe($post->id);
});

it('loads a camelCase relation', function () {
    $bookCase = BookCase::create([
        'name' => fake()->name,
    ]);

    $book = $bookCase->books()->create([
        'name' => fake()->name,
    ]);

    expect($bookCase->books)->toHaveCount(1)
        ->and($bookCase->books->first()->id)->toBe($book->id)
        ->and($book->bookCase->id)->toBe($bookCase->id)
        ->and($book->book_case_id)->toBe($bookCase->id);

    $bookCase = BookCase::query()->find($bookCase->id);
    expect($bookCase->books)->toHaveCount(1)
        ->and($bookCase->books->first()->id)->toBe($book->id);

    $book = Book::query()->find($book->id);
    expect($book->bookCase->id)->toBe($bookCase->id);
});
