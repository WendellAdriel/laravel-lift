<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;
use WendellAdriel\Lift\Contracts\RelationAttribute;

trait ManageRelations
{
    /**
     * @var array<class-string, RelationAttribute>
     */
    private static ?array $relationsConfig = null;

    /**
     * @return array<class-string, RelationAttribute>
     */
    private static function relationsConfig(Model $model): array
    {
        if (is_null(self::$relationsConfig)) {
            self::$relationsConfig = [];
            self::buildRelations(new static());
        }

        return self::$relationsConfig[$model::class] ?? [];
    }

    private static function buildRelations(Model $model): void
    {
        $classReflection = new ReflectionClass($model);
        $attributes = $classReflection->getAttributes(RelationAttribute::class, ReflectionAttribute::IS_INSTANCEOF);

        foreach ($attributes as $attribute) {
            /** @var RelationAttribute $relation */
            $relation = $attribute->newInstance();
            $relationArguments = $relation->relationArguments();

            $model::resolveRelationUsing(
                $relation->relationName(),
                function (Model $model) use ($relation, $relationArguments, &$relationObject): Relation {
                    $method = lcfirst(class_basename(get_class($relation)));

                    return $model->{$method}(...$relationArguments);
                }
            );

            if ($relation instanceof BelongsTo) {
                self::$relationsConfig[$model::class][$relation->relationClass ?? $relation->morphName] = $relation;
            }
        }
    }

    private static function handleRelationsKeys(Model $model): void
    {
        foreach (self::relationsConfig($model) as $relatedClass => $relationConfig) {
            $related = new $relatedClass();

            $foreignKey = $relationConfig->relationArguments()[1] ?? Str::snake($relationConfig->relationName()) . '_' . $related->getKeyName();
            if (! isset($model->{$foreignKey}) || blank($model->{$foreignKey})) {
                $model->{$foreignKey} = $model->getAttribute($foreignKey);
            }
        }
    }
}
