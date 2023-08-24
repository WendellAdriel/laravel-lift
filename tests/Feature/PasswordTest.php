<?php

declare(strict_types=1);

use Illuminate\Validation\ValidationException;
use WendellAdriel\Lift\Tests\Datasets\UserPassword;

describe('Create model', function () {
    it('throws validation error if password is invalid', function () {
        UserPassword::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3cr3t',
        ]);
    })->throws(ValidationException::class);

    it('does not throw validation error if password is valid', function () {
        $password = 's3Cr3t@!!!';
        $user = UserPassword::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => $password,
        ]);

        expect($user->fresh()->password)->toBe($password);
    });
});

describe('Update using update method', function () {
    it('throws validation error if password is invalid', function () {
        $user = UserPassword::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->update([
            'password' => 's3cr3t',
        ]);
    })->throws(ValidationException::class);

    it('does not throw validation error if password is valid', function () {
        $user = UserPassword::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $newPassword = 's3cr3t@@@@!!!!';
        $user->update([
            'password' => $newPassword,
        ]);

        expect($user->fresh()->password)->toBe($newPassword);
    });
});

describe('Update using fill + save method', function () {
    it('throws validation error if password is invalid', function () {
        $user = UserPassword::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->fill([
            'password' => 's3cr3t',
        ]);
        $user->save();
    })->throws(ValidationException::class);

    it('does not throw validation error if password is valid', function () {
        $user = UserPassword::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $newPassword = 's3cr3t@@@@!!!!';
        $user->fill([
            'password' => $newPassword,
        ]);
        $user->save();

        expect($user->fresh()->password)->toBe($newPassword);
    });
});

describe('Update changing individual properties', function () {
    it('throws validation error if password is invalid', function () {
        $user = UserPassword::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->password = 's3cr3t';
        $user->save();
    })->throws(ValidationException::class);

    it('does not throw validation error if password is valid', function () {
        $user = UserPassword::create([
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $newPassword = 's3cr3t@@@@!!!!';
        $user->password = $newPassword;
        $user->save();

        expect($user->fresh()->password)->toBe($newPassword);
    });
});
