<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as BaseBuilder;
use ReflectionClass;
use WendellAdriel\Lift\Attributes\QueryBuilder;

trait HasQueryBuilder
{
    /**
     * @param  BaseBuilder  $query
     * @return BuilderContract|Builder
     */
    public function newEloquentBuilder($query)
    {
        $classReflection = new ReflectionClass(static::class);
        $queryBuilderAttributes = $classReflection->getAttributes(QueryBuilder::class);

        if (empty($queryBuilderAttributes)) {
            return new Builder($query);
        }

        $queryBuilderAttribute = $queryBuilderAttributes[0];
        /** @var QueryBuilder $queryBuilderAttributeInstance */
        $queryBuilderAttributeInstance = $queryBuilderAttribute->newInstance();

        return new ($queryBuilderAttributeInstance->builderClass)($query);
    }
}
