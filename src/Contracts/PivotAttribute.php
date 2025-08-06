<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Contracts;

interface PivotAttribute
{
    public function pivotModel(): ?string;

    /**
     * @return array<string>|null
     */
    public function pivotColumns(): ?array;

    public function pivotTimestamps(): ?bool;
}
