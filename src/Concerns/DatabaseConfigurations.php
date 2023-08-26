<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\DB;

trait DatabaseConfigurations
{
    private static ?array $modelCustomColumns = null;

    private static ?array $modelDefaultValues = null;

    public static function customColumns(): array
    {
        if (is_null(self::$modelCustomColumns)) {
            self::$modelCustomColumns = [];
            self::$modelDefaultValues = [];
            self::buildCustomColumns(new static());
        }

        return self::$modelCustomColumns;
    }

    public static function defaultValues(): array
    {
        if (is_null(self::$modelDefaultValues)) {
            self::$modelCustomColumns = [];
            self::$modelDefaultValues = [];
            self::buildCustomColumns(new static());
        }

        return self::$modelDefaultValues;
    }

    private static function buildCustomColumns(Model $model): void
    {
        $properties = self::getPropertiesWithAtributes($model);

        $customColumns = self::getPropertiesForAttributes($properties, [Column::class]);
        $customColumns->each(function ($property) {
            $columnAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Column::class);
            if (blank($columnAttribute)) {
                return;
            }

            $columnAttribute = $columnAttribute->newInstance();
            if (! is_null($columnAttribute->name)) {
                self::$modelCustomColumns[$property->name] = $columnAttribute->name;
            }
            if (! is_null($columnAttribute->default)) {
                self::$modelDefaultValues[$property->name] = $columnAttribute->default;
            }
        });
    }

    private static function syncCostumColumns(Model $model): void
    {
        $publicProperties = self::getModelPublicProperties($model);
        $defaultValues = self::defaultValues();
        $customColumns = self::customColumns();

        foreach ($publicProperties as $property) {
            if (! blank($model->getAttribute($property)) && isset($customColumns[$property])) {
                $model->{$property} = $model->getAttribute($property);
                unset($model->attributes[$property]);
            }

            if (
                (! isset($model->{$property}) || blank($model->{$property})) &&
                isset($defaultValues[$property])
            ) {
                $model->{$property} = method_exists($model, $defaultValues[$property])
                    ? $model->{$defaultValues[$property]}()
                    : $defaultValues[$property];
            }

            if (! isset($model->{$property})) {
                continue;
            }

            if (isset($customColumns[$property])) {
                $model->setAttribute($customColumns[$property], $model->{$property});

                continue;
            }

            if (blank($model->getAttribute($property)) && ! blank($model->{$property})) {
                $model->setAttribute($property, $model->{$property});
            }
        }
    }

    private static function syncColumnsToCustom(Model $model): void
    {
        foreach (self::customColumns() as $property => $column) {
            $model->{$property} = $model->getAttribute($column);
        }
    }

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
