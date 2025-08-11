<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

trait HasPivot
{
    public function pivotModel(): ?string
    {
        return $this->pivotModel;
    }

    /**
     * @return array<string>|null
     */
    public function pivotColumns(): ?array
    {
        return $this->pivotColumns;
    }

    public function pivotTimestamps(): ?bool
    {
        return $this->pivotTimestamps;
    }
}
