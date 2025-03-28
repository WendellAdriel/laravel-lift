<?php

declare(strict_types=1);

namespace Tests\Datasets\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationUser extends Pivot
{
    protected $casts = [
        'is_owner' => 'boolean',
    ];
}
