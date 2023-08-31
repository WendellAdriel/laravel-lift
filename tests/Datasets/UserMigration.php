<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Carbon\CarbonImmutable;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

#[DB(table: 'users_migration')]
final class UserMigration extends Model
{
    use Lift, SoftDeletes;

    #[PrimaryKey]
    public int $id;

    #[Rules(['required', 'string'], ['required' => 'The user name cannot be empty'])]
    public string $name;

    #[Rules(['required', 'email'])]
    public string $email;

    #[Rules(['required', 'string', 'min:8'])]
    public string $password;

    public CarbonImmutable $created_at;

    public DateTime $updated_at;

    public ?bool $active;

    public $test;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
