<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use Tests\Datasets\UserNull;
use Tests\Datasets\UserNullCustom;

describe('can handle null values', function () {
    it('can set null values directly', function () {
        $user = UserNull::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
            'email_verified_at' => Carbon::now(),
        ]);

        expect($user->email_verified_at)
            ->not->toBeNull()
            ->toBeInstanceOf(Carbon::class);

        $user->email_verified_at = null;
        $user->save();

        expect($user->email_verified_at)->toBeNull();

        $this->assertDatabaseHas(UserNull::class, [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
            'email_verified_at' => null,
        ]);
    });

    it('can handle null values for custom columns', function () {
        $user = UserNullCustom::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
            'email_verified_at_custom' => Carbon::now(),
        ]);

        expect($user->email_verified_at_custom)
            ->not->toBeNull()
            ->toBeInstanceOf(Carbon::class);

        $user->email_verified_at_custom = null;
        $user->save();

        expect($user->email_verified_at_custom)->toBeNull();

        $this->assertDatabaseHas(UserNull::class, [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 's3Cr3T@!!!',
            'email_verified_at' => null,
        ]);
    });
});
