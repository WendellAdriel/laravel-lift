<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Concerns;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use WendellAdriel\Lift\Attributes\Column;
use WendellAdriel\Lift\Attributes\Config;
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
            self::buildCustomColumns(new static);
        }

        return self::$modelCustomColumns;
    }

    public static function defaultValues(): array
    {
        if (is_null(self::$modelDefaultValues)) {
            self::$modelCustomColumns = [];
            self::$modelDefaultValues = [];
            self::buildCustomColumns(new static);
        }

        return self::$modelDefaultValues;
    }

    private static function buildCustomColumns(Model $model): void
    {
        $properties = self::getPropertiesWithAttributes($model);

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

        $configColumns = self::getPropertiesForAttributes($properties, [Config::class]);
        $configColumns->each(function ($property) {
            $configAttribute = $property->attributes->first(fn ($attribute) => $attribute->getName() === Config::class);
            if (blank($configAttribute)) {
                return;
            }

            $configAttribute = $configAttribute->newInstance();
            if (! is_null($configAttribute->column)) {
                self::$modelCustomColumns[$property->name] = $configAttribute->column;
            }
            if (! is_null($configAttribute->default)) {
                self::$modelDefaultValues[$property->name] = $configAttribute->default;
            }
        });
    }

    private static function syncCustomColumns(Model $model): void
    {
        $publicProperties = self::getModelPublicProperties($model);
        $defaultValues = self::defaultValues();
        $customColumns = self::customColumns();

        foreach ($publicProperties as $property) {
            if (isset($customColumns[$property]) && $model->isDirty($customColumns[$property]) && ! blank($model->getAttribute($customColumns[$property]))) {
                $model->{$property} = $model->getAttribute($customColumns[$property]);
            }

            if (! blank($model->getAttribute($property)) && isset($customColumns[$property])) {
                $model->{$property} = $model->getAttribute($property);
                unset($model->attributes[$property]);
            }

            if (
                ! isset($model->{$property}) &&
                array_key_exists($property, $defaultValues)
            ) {
                $model->{$property} = self::resolveDefaultValue($model, $defaultValues[$property]);
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
        $attributes = $model->getAttributes();

        foreach (self::customColumns() as $property => $column) {
            if (array_key_exists($column, $attributes)) {
                $model->{$property} = $model->getAttribute($column);
            }
        }
    }

    private static function applyDefaultValues(Model $model): void
    {
        $defaultValues = self::defaultValues();

        foreach (self::getModelPublicReflectionProperties($model) as $property) {
            $propertyName = $property->getName();

            if (! array_key_exists($propertyName, $defaultValues) || $property->isInitialized($model)) {
                continue;
            }

            $model->{$propertyName} = self::resolveDefaultValue($model, $defaultValues[$propertyName]);
        }
    }

    private static function resolveDefaultValue(Model $model, mixed $defaultValue): mixed
    {
        return is_string($defaultValue) && method_exists($model, $defaultValue)
            ? $model->{$defaultValue}()
            : $defaultValue;
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
