<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use WendellAdriel\Lift\Attributes\CreateRules;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Attributes\UpdateRules;
use WendellAdriel\Lift\Lift;

#[DB(table: 'users')]
class UserMethodContextRules extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    #[Rules(['required'])]
    #[CreateRules(['createNameRule'])]
    #[UpdateRules(['updateNameRule'])]
    public string $name;

    #[Fillable]
    public string $email;

    #[Fillable]
    public string $password;

    public function createNameRule(Model $model): mixed
    {
        return Rule::notIn(['blocked-create']);
    }

    public function updateNameRule(Model $model): mixed
    {
        return Rule::notIn(['blocked-update']);
    }
}
