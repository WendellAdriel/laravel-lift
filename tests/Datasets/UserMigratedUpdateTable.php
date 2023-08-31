<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

#[DB(table: 'users_migrated')]
final class UserMigratedUpdateTable extends Model
{
    use Lift, SoftDeletes;

    #[PrimaryKey]
    public int $id;

    #[Rules(['required', 'string'], ['required' => 'The user name cannot be empty'])]
    public string $name;

    public string $username;

    #[Rules(['required', 'email'])]
    public string $email;

    #[Rules(['required', 'string', 'min:8'])]
    public string $password;

    public ?bool $active;
}
