<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;
use WendellAdriel\Lift\Attributes\Relations\HasMany;
use WendellAdriel\Lift\Attributes\Relations\HasOne;
use WendellAdriel\Lift\Attributes\Relations\MorphOne;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

#[BelongsToMany(related: Role::class, name: 'roleList')]
#[HasMany(related: Post::class, name: 'articles')]
#[HasMany(WorkBook::class)]
#[HasOne(Phone::class)]
#[MorphOne(Image::class, 'imageable')]
class User extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Rules(['required', 'string'], ['required' => 'The user name cannot be empty'])]
    public string $name;

    #[Rules(['required', 'email'])]
    public string $email;

    #[Rules(['required', 'string', 'min:8'])]
    public string $password;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
