<?php

declare(strict_types=1);

namespace Tests\Datasets\Enums;

enum ArticleStatusEnum: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
}
