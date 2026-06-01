<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Config;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Lift;

#[DB(table: 'users')]
class UserConfigColumn extends Model
{
    use Lift;

    #[Config(fillable: true)]
    public string $name;

    #[Config(fillable: true, column: 'email')]
    public string $user_email;

    #[Config(fillable: true, column: 'password')]
    public string $user_password;
}
