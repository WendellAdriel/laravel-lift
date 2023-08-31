<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

#[DB(connection: 'mysql', table: 'users_custom_db', timestamps: false)]
class UserCustomDB extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Rules(['required', 'string'], ['required' => 'The user name cannot be empty'])]
    #[Fillable]
    public string $name;

    #[Rules(['required', 'email'])]
    #[Fillable]
    public string $email;

    public ?string $password;
}
