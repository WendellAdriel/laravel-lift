<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

#[DB(table: 'users_null')]
class UserNullCustom extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Rules(['required', 'string'], ['required' => 'The user name cannot be empty'])]
    public string $name;

    #[Rules(['required', 'email'])]
    public string $email;

    #[Cast('datetime')]
    #[Column(name: 'email_verified_at')]
    public ?Carbon $email_verified_at_custom;

    #[Rules(['required', 'string', 'min:8'])]
    public string $password;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at_custom',
    ];
}
