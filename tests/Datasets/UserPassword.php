<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Password;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

class UserPassword extends Model
{
    use Lift;

    #[Rules(['required', 'string'])]
    public string $name;

    #[Rules(['required', 'email'])]
    public string $email;

    #[Password(mixedCase: true, numbers: true, symbols: true, uncompromised: true)]
    public string $password;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
