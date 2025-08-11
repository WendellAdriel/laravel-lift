<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use Tests\Datasets\Pivot\OrganizationUser;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;
use WendellAdriel\Lift\Lift;

#[BelongsToMany(User::class, pivotModel: OrganizationUser::class, pivotColumns: ['is_owner'])]
class Organization extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    public string $name;
}
