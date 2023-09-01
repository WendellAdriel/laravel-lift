<?php

declare(strict_types=1);

use Illuminate\Validation\ValidationException;
use Tests\Datasets\User;
use Tests\Datasets\UserRules;

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

describe('CREATE RULES', function () {
    it('throws validation error if model data is invalid', function () {
        UserRules::create([
            'name' => fake()->name,
            'email' => 'test',
            'password' => 's3Cr3t@!!!',
        ]);
    })->throws(ValidationException::class);

    it('does not throw validation error if model data is valid', function () {
        $user = UserRules::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $this->assertDatabaseCount(UserRules::class, 1);
        $this->assertDatabaseHas(UserRules::class, [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    });
});

describe('UPDATE RULES', function () {
    it('throws validation error if model data is invalid', function () {
        $user = UserRules::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->password = '123';
        $user->save();
    })->throws(ValidationException::class);

    it('does not throw validation error if model data is valid', function () {
        $user = UserRules::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->password = 's3Cr3t@!!!123456';
        $user->save();

        $this->assertDatabaseCount(UserRules::class, 1);
        $this->assertDatabaseHas(UserRules::class, [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 's3Cr3t@!!!123456',
        ]);
    });
});

describe('Gets model validation rules and messages statically', function () {
    it('gets validation rules', function () {
        expect(User::validationRules())->toBe([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    });

    it('gets create validation rules', function () {
        expect(UserRules::createValidationRules())->toBe([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        expect(UserRules::validationRules())->toBe([
            'name' => ['required', 'string'],
        ]);
    });

    it('gets update validation rules', function () {
        expect(UserRules::updateValidationRules())->toBe([
            'email' => ['sometimes', 'email'],
            'password' => ['sometimes', 'string', 'min:8'],
        ]);

        expect(UserRules::validationRules())->toBe([
            'name' => ['required', 'string'],
        ]);
    });

    it('gets validation messages', function () {
        expect(User::validationMessages())->toBe([
            'name' => [
                'required' => 'The user name cannot be empty',
            ],
            'email' => [],
            'password' => [],
        ]);
    });

    it('gets create validation messages', function () {
        expect(UserRules::createValidationMessages())->toBe([
            'email' => [
                'required' => 'The user email cannot be empty',
            ],
            'password' => [],
        ]);

        expect(UserRules::validationMessages())->toBe([
            'name' => [
                'required' => 'The user name cannot be empty',
            ],
        ]);
    });

    it('gets update validation messages', function () {
        expect(UserRules::updateValidationMessages())->toBe([
            'email' => [],
            'password' => [
                'min' => 'The password must be at least 8 characters long',
            ],
        ]);

        expect(UserRules::validationMessages())->toBe([
            'name' => [
                'required' => 'The user name cannot be empty',
            ],
        ]);
    });
});
