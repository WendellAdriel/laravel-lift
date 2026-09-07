<?php

declare(strict_types=1);

use Tests\Datasets\UserColumn;
use Tests\Datasets\UserConfigColumn;

it('returns model custom columns', function () {
    expect(UserColumn::customColumns())->toBe([
        'user_email' => 'email',
        'user_password' => 'password',
    ]);
});

it('returns model default values', function () {
    expect(UserColumn::defaultValues())->toBe([
        'name' => 'John Doe',
        'active' => false,
        'user_password' => 'generatePassword',
    ]);
});

it('returns array with model properties when custom columns are defined', function () {
    $user = UserColumn::create([
        'name' => fake()->name,
        'user_email' => fake()->unique()->safeEmail,
        'user_password' => 's3Cr3T@!!!',
    ]);

    expect($user->toArray())->toHaveKeys([
        'id',
        'name',
        'user_email',
        'user_password',
        'created_at',
        'updated_at',
    ]);
});

it('returns json with model properties when custom columns are defined', function () {
    $user = UserColumn::create([
        'name' => fake()->name,
        'user_email' => fake()->unique()->safeEmail,
        'user_password' => 's3Cr3T@!!!',
    ]);

    expect($user->toJson())->toBe(json_encode($user->toArray()));
});

describe('creates new model with custom columns', function () {
    it('initializes default public properties when cast and fill omits them', function () {
        $user = new UserColumn;
        $user->castAndFill([
            'user_email' => 'john.doe@example.com',
        ]);

        expect($user->name)->toBe('John Doe')
            ->and($user->active)->toBeFalse()
            ->and($user->user_password)->toBe('s3Cr3tP4ssw0rd@!!!');
    });

    it('keeps explicitly filled public properties over defaults when cast and fill is used', function () {
        $user = new UserColumn;
        $user->castAndFill([
            'name' => 'Jane Doe',
            'user_email' => 'john.doe@example.com',
            'user_password' => 's3Cr3T@!!!',
            'active' => true,
        ]);

        expect($user->name)->toBe('Jane Doe')
            ->and($user->active)->toBeTrue()
            ->and($user->user_password)->toBe('s3Cr3T@!!!');
    });

    it('creates model with individual properties set', function () {
        $user = new UserColumn;
        $user->name = fake()->name;
        $user->user_email = fake()->unique()->safeEmail;
        $user->user_password = 's3Cr3T@!!!';
        $user->save();

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => $user->name,
            'email' => $user->user_email,
            'password' => $user->user_password,
        ]);
    });

    it('creates model with fill method', function () {
        $user = new UserColumn;
        $user->fill([
            'name' => fake()->name,
            'user_email' => fake()->unique()->safeEmail,
            'user_password' => 's3Cr3T@!!!',
        ]);
        $user->save();

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => $user->name,
            'email' => $user->user_email,
            'password' => $user->user_password,
        ]);
    });

    it('creates model with create method', function () {
        $user = UserColumn::create([
            'name' => fake()->name,
            'user_email' => fake()->unique()->safeEmail,
            'user_password' => 's3Cr3T@!!!',
        ]);

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => $user->name,
            'email' => $user->user_email,
            'password' => $user->user_password,
        ]);
    });

    it('creates model with create method using mapped database columns', function () {
        $user = UserColumn::create([
            'name' => fake()->name,
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
        ]);

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => $user->name,
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
        ]);
    });

    it('ignores unrelated non-fillable keys when using mapped database columns', function () {
        $user = new UserColumn;
        $user->fill([
            'name' => fake()->name,
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
            'not_fillable' => 'ignored',
        ]);

        expect($user->getAttributes())->not->toHaveKey('not_fillable');
    });

    it('creates model with default values', function () {
        UserColumn::create([
            'user_email' => 'john.doe@example.com',
        ]);

        $this->assertDatabaseHas(UserColumn::class, [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3tP4ssw0rd@!!!',
        ]);
    });
});

describe('updates model with custom columns', function () {
    it('updates model by updating individual properties', function () {
        $user = UserColumn::create([
            'name' => fake()->name,
            'user_email' => fake()->unique()->safeEmail,
            'user_password' => 's3Cr3T@!!!',
        ]);

        $user->user_email = 'john.doe@example.com';
        $user->save();

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => $user->name,
            'email' => 'john.doe@example.com',
            'password' => $user->user_password,
        ]);
    });

    it('updates model with fill method', function () {
        $user = UserColumn::create([
            'name' => fake()->name,
            'user_email' => fake()->unique()->safeEmail,
            'user_password' => 's3Cr3T@!!!',
        ]);

        $user->fill([
            'name' => 'John Doe',
            'user_email' => 'john.doe@example.com',
        ]);
        $user->save();

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
        ]);
    });

    it('updates model with fill method using mapped database columns', function () {
        $user = UserColumn::create([
            'name' => fake()->name,
            'user_email' => fake()->unique()->safeEmail,
            'user_password' => 's3Cr3T@!!!',
        ]);

        $user->fill([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);
        $user->save();

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
        ]);
    });

    it('updates model with update method', function () {
        $user = UserColumn::create([
            'name' => fake()->name,
            'user_email' => fake()->unique()->safeEmail,
            'user_password' => 's3Cr3T@!!!',
        ]);

        $user->update([
            'name' => 'John Doe',
            'user_email' => 'john.doe@example.com',
        ]);

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
        ]);
    });

    it('updates model with update method using mapped database columns', function () {
        $user = UserColumn::create([
            'name' => fake()->name,
            'user_email' => fake()->unique()->safeEmail,
            'user_password' => 's3Cr3T@!!!',
        ]);

        $user->update([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $this->assertDatabaseCount(UserColumn::class, 1);
        $this->assertDatabaseHas(UserColumn::class, [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
        ]);
    });
});

it('creates model with Config fillable mapped database columns', function () {
    $user = UserConfigColumn::create([
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 's3Cr3T@!!!',
    ]);

    $this->assertDatabaseCount(UserConfigColumn::class, 1);
    $this->assertDatabaseHas(UserConfigColumn::class, [
        'name' => $user->name,
        'email' => 'john.doe@example.com',
        'password' => 's3Cr3T@!!!',
    ]);
});

it('retrieves model with all custom columns and properties set', function () {
    UserColumn::create([
        'name' => 'John Doe',
        'user_email' => 'john.doe@example.com',
        'user_password' => 's3Cr3T@!!!',
    ]);
    $user = UserColumn::query()->first();

    expect($user->name)->toBe('John Doe')
        ->and($user->user_email)->toBe('john.doe@example.com')
        ->and($user->user_password)->toBe('s3Cr3T@!!!')
        ->and($user->email)->toBe('john.doe@example.com')
        ->and($user->password)->toBe('s3Cr3T@!!!');
});

it('initializes default public properties omitted from partial selects', function () {
    UserColumn::create([
        'name' => 'Jane Doe',
        'user_email' => 'john.doe@example.com',
        'user_password' => 's3Cr3T@!!!',
    ]);

    $user = UserColumn::query()
        ->select(['id', 'email'])
        ->first();

    expect($user->name)->toBe('John Doe')
        ->and($user->active)->toBeFalse()
        ->and($user->user_password)->toBe('s3Cr3tP4ssw0rd@!!!')
        ->and($user->user_email)->toBe('john.doe@example.com');
});
