<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Tests\Datasets;

use WendellAdriel\Lift\Attributes\Relations\BelongsTo;

#[BelongsTo(User::class)]
class WorkBook extends Book
{
}
