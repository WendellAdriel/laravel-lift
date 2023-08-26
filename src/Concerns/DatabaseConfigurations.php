<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use ReflectionClass;
use WendellAdriel\Lift\Attributes\DB;

trait DatabaseConfigurations
{
    private function applyDatabaseConfigurations(): void
    {
        $classReflection = new ReflectionClass($this);
        $dbAttribute = $classReflection->getAttributes(DB::class)[0] ?? null;

        if (! blank($dbAttribute)) {
            $dbAttribute = $dbAttribute->newInstance();

            if (! is_null($dbAttribute->connection)) {
                $this->setConnection($dbAttribute->connection);
            }

            if (! is_null($dbAttribute->table)) {
                $this->setTable($dbAttribute->table);
            }

            $this->timestamps = $dbAttribute->timestamps;
        }
    }
}
