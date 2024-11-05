<?php

declare(strict_types=1);

namespace Tests\Datasets;

use Illuminate\Database\Eloquent\Model;
use Tests\Datasets\Enums\ArticleStatusEnum;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Rules;
use WendellAdriel\Lift\Lift;

class Article extends Model
{
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Cast(ArticleStatusEnum::class)]
    #[Rules(['required', 'string', 'in:draft.published,archived'], ['required' => 'The book name cannot be empty', 'in' => 'The status must be draft, published or archived'])]
    public ArticleStatusEnum $status;

    protected $fillable = [
        'status',
    ];
}
