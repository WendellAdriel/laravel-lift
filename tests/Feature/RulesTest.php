<?php

declare(strict_types=1);

use Illuminate\Validation\ValidationException;
use WendellAdriel\Lift\Tests\Datasets\User;

describe('Create model', function () {
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
});

describe('Update using update method', function () {
    it('throws validation error if model data is invalid on update', function () {
        $user = User::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->update([
            'email' => fake()->word(),
        ]);
    })->throws(ValidationException::class);

    it('does not throw validation error if model data is valid on update', function () {
        $user = User::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->update([
            'email' => 'updated@example.com',
        ]);

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, [
            'name' => $user->name,
            'email' => 'updated@example.com',
        ]);
    });
});

describe('Update using fill + save method', function () {
    it('throws validation error if model data is invalid on update', function () {
        $user = User::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->fill([
            'email' => fake()->word(),
        ]);
        $user->save();
    })->throws(ValidationException::class);

    it('does not throw validation error if model data is valid on update', function () {
        $user = User::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->fill([
            'email' => 'updated@example.com',
        ]);
        $user->save();

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, [
            'name' => $user->name,
            'email' => 'updated@example.com',
        ]);
    });
});

describe('Update changing individual properties', function () {
    it('throws validation error if model data is invalid on update', function () {
        $user = User::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->email = fake()->word();
        $user->save();
    })->throws(ValidationException::class);

    it('does not throw validation error if model data is valid on update', function () {
        $user = User::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->email = 'updated@example.com';
        $user->save();

        $this->assertDatabaseCount(User::class, 1);
        $this->assertDatabaseHas(User::class, [
            'name' => $user->name,
            'email' => 'updated@example.com',
        ]);
    });
});

describe('Gets model validation rules and messages statically', function () {
    it('gets validation rules', function () {
        $rules = User::validationRules();

        expect($rules)->toBe([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    });

    it('gets validation messages', function () {
        $messages = User::validationMessages();

        expect($messages)->toBe([
            'name' => [
                'required' => 'The user name cannot be empty',
            ],
            'email' => [],
            'password' => [],
        ]);
    });
});
