<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

#[DB(table: 'users')]
class UserMethodRules extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    #[Rules(['required', 'uniqueNameRule'])]
    public string $name;

    #[Fillable]
    public string $email;

    #[Fillable]
    public string $password;

    public function uniqueNameRule(Model $model): mixed
    {
        return Rule::unique('users', 'name')->ignore($model->getKey());
    }
}
