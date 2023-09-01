<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\CreateRules;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Attributes\UpdateRules;
use WendellAdriel\Lift\Lift;

#[DB(table: 'users')]
class UserRules extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    #[Rules(rules: ['required', 'string'], messages: ['required' => 'The user name cannot be empty'])]
    public string $name;

    #[Fillable]
    #[CreateRules(rules: ['required', 'email'], messages: ['required' => 'The user email cannot be empty'])]
    #[UpdateRules(['sometimes', 'email'])]
    public string $email;

    #[Fillable]
    #[CreateRules(['required', 'string', 'min:8'])]
    #[UpdateRules(rules: ['sometimes', 'string', 'min:8'], messages: ['min' => 'The password must be at least 8 characters long'])]
    public string $password;
}
