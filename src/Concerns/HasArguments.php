<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

trait HasArguments
{
    /**
     * @return array<mixed>
     */
    public function relationArguments(): array
    {
        return $this->arguments;
    }
}
