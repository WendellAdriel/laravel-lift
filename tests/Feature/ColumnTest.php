<?php

declare(strict_types=1);

use Tests\Datasets\UserColumn;

it('returns model custom columns', function () {
    expect(UserColumn::customColumns())->toBe([
        'user_email' => 'email',
        'user_password' => 'password',
    ]);
});

it('returns model default values', function () {
    expect(UserColumn::defaultValues())->toBe([
        'name' => 'John Doe',
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
    it('creates model with individual properties set', function () {
        $user = new UserColumn();
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
        $user = new UserColumn();
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
