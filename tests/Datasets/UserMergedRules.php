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
class UserMergedRules extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    #[Rules(rules: ['required', 'alpha'], messages: ['alpha' => 'The name must contain only letters'])]
    #[CreateRules(rules: ['min:5'], messages: ['min' => 'The name must have at least 5 characters on create'])]
    #[UpdateRules(rules: ['max:10'], messages: ['max' => 'The name must have at most 10 characters on update'])]
    public string $name;

    #[Fillable]
    public string $email;

    #[Fillable]
    public string $password;
}
