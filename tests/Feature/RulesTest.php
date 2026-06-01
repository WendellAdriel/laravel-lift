<?php

declare(strict_types=1);

use Illuminate\Validation\ValidationException;
use Tests\Datasets\User;
use Tests\Datasets\UserMergedRules;
use Tests\Datasets\UserMethodContextRules;
use Tests\Datasets\UserMethodRules;
use Tests\Datasets\UserRules;

it('throws validation errors for locale', function () {
    file_put_contents(
        base_path('lang/pt.json'),
        json_encode([
            'validation.required' => 'O campo :attribute é obrigatório',
        ])
    );
    $this->app->setLocale('pt');

    User::create([
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
    ]);

    $this->assertSessionHasErrors([
        'name' => 'O campo password é obrigatório',
    ]);
})->throws(ValidationException::class);

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

describe('Overlapping base and context rules', function () {
    it('keeps base rules when create rules target the same property', function () {
        UserMergedRules::create([
            'name' => '12345',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);
    })->throws(ValidationException::class);

    it('keeps create rules when base rules target the same property', function () {
        UserMergedRules::create([
            'name' => 'abcd',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);
    })->throws(ValidationException::class);

    it('keeps base rules when update rules target the same property', function () {
        $user = UserMergedRules::create([
            'name' => 'abcde',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->name = '12345';
        $user->save();
    })->throws(ValidationException::class);

    it('keeps update rules when base rules target the same property', function () {
        $user = UserMergedRules::create([
            'name' => 'abcde',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->name = 'abcdefghijk';
        $user->save();
    })->throws(ValidationException::class);

    it('creates a model when merged base and create rules pass', function () {
        $user = UserMergedRules::create([
            'name' => 'abcde',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $this->assertDatabaseHas(UserMergedRules::class, [
            'name' => $user->name,
        ]);
    });

    it('updates a model when merged base and update rules pass', function () {
        $user = UserMergedRules::create([
            'name' => 'abcde',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->name = 'abcdef';
        $user->save();

        $this->assertDatabaseHas(UserMergedRules::class, [
            'name' => 'abcdef',
        ]);
    });

    it('keeps base and context validation messages for the same property', function () {
        try {
            UserMergedRules::create([
                'name' => '1234',
                'email' => fake()->unique()->safeEmail,
                'password' => 's3Cr3t@!!!',
            ]);
        } catch (ValidationException $exception) {
            expect($exception->validator->errors()->get('name'))->toContain(
                'The name must contain only letters',
                'The name must have at least 5 characters on create',
            );

            return;
        }

        $this->fail('Expected validation to fail for merged base and create messages.');
    });
});

describe('Method-backed base validation rules', function () {
    it('rejects duplicate names when creating a model', function () {
        UserMethodRules::create([
            'name' => 'ExistingName',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        try {
            UserMethodRules::create([
                'name' => 'ExistingName',
                'email' => fake()->unique()->safeEmail,
                'password' => 's3Cr3t@!!!',
            ]);
        } catch (ValidationException $exception) {
            expect($exception->validator->errors()->has('name'))->toBeTrue();

            return;
        }

        $this->fail('Expected Laravel validation to reject a duplicate name.');
    });

    it('allows updating a model without failing its own unique name', function () {
        $user = UserMethodRules::create([
            'name' => 'OriginalName',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->name = 'OriginalName';
        $user->save();

        $this->assertDatabaseHas(UserMethodRules::class, [
            'name' => 'OriginalName',
        ]);
    });

    it('rejects updating a model name to another model name', function () {
        $firstUser = UserMethodRules::create([
            'name' => 'FirstName',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);
        $secondUser = UserMethodRules::create([
            'name' => 'SecondName',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        try {
            $secondUser->name = $firstUser->name;
            $secondUser->save();
        } catch (ValidationException $exception) {
            expect($exception->validator->errors()->has('name'))->toBeTrue();

            return;
        }

        $this->fail('Expected Laravel validation to reject an existing name on update.');
    });
});

describe('Method-backed create and update validation rules', function () {
    it('allows creating a model when a method-backed create rule passes', function () {
        $user = UserMethodContextRules::create([
            'name' => 'allowed-create',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $this->assertDatabaseHas(UserMethodContextRules::class, [
            'name' => $user->name,
        ]);
    });

    it('rejects creating a model when a method-backed create rule fails', function () {
        UserMethodContextRules::create([
            'name' => 'blocked-create',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);
    })->throws(ValidationException::class);

    it('allows updating a model when a method-backed update rule passes', function () {
        $user = UserMethodContextRules::create([
            'name' => 'allowed-create',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->name = 'allowed-update';
        $user->save();

        $this->assertDatabaseHas(UserMethodContextRules::class, [
            'name' => 'allowed-update',
        ]);
    });

    it('rejects updating a model when a method-backed update rule fails', function () {
        $user = UserMethodContextRules::create([
            'name' => 'allowed-create',
            'email' => fake()->unique()->safeEmail,
            'password' => 's3Cr3t@!!!',
        ]);

        $user->name = 'blocked-update';
        $user->save();
    })->throws(ValidationException::class);
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
        ]);
    });

    it('gets create validation messages', function () {
        expect(UserRules::createValidationMessages())->toBe([
            'email' => [
                'required' => 'The user email cannot be empty',
            ],
        ]);

        expect(UserRules::validationMessages())->toBe([
            'name' => [
                'required' => 'The user name cannot be empty',
            ],
        ]);
    });

    it('gets update validation messages', function () {
        expect(UserRules::updateValidationMessages())->toBe([
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
