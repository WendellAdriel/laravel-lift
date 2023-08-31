<?php

declare(strict_types=1);

namespace Tests\Datasets;

use WendellAdriel\Lift\Attributes\Relations\BelongsTo;

#[BelongsTo(Library::class)]
class LibraryBook extends Book
{
}
