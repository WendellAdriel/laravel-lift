<?php

use Illuminate\Support\Carbon;
use Tests\Datasets\UserNull;

it('can set null values', function () {
    $user = UserNull::create([
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 's3Cr3T@!!!',
        'email_verified_at' => Carbon::now(),
    ]);

    expect($user->email_verified_at)->toBeInstanceOf(Carbon::class);

    $user->email_verified_at = null;
    $user->save();

    $this->assertDatabaseHas(UserNull::class, [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'password' => 's3Cr3T@!!!',
        'email_verified_at' => null,
    ]);
});
