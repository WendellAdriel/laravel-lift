<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use Symfony\Component\Console\Attribute\AsCommand;
use Throwable;
use WendellAdriel\Lift\Lift;

#[AsCommand(name: 'lift:migration', description: 'Create migration files based on Lift models')]
final class LiftMigration extends Command
{
    protected $name = 'lift:migration';

    protected $signature = 'lift:migration {model}
                            {--namespace=App\\Models\\}';

    protected static $defaultName = 'lift:migration';

    protected $description = 'Create migration files based on Lift models';

    public function handle(): int
    {
        try {
            $class = $this->option('namespace') . '\\' . $this->argument('model'); // @phpstan-ignore-line

            if (! class_exists($class)) {
                $this->error("Model {$class} not found.");

                return Command::FAILURE;
            }

            if (! in_array(Lift::class, class_uses($class))) {
                $this->error("Model {$class} does not use Lift trait.");

                return Command::FAILURE;
            }

            /** @var Model $model */
            $model = new $class();
            $table = $model->getTable();

            $this->info("Generating migration file for model {$class} (table {$table})");
            $modelProperties = $this->buildModelPropertiesList($class);
            $migrationCalls = $this->buildMigrationCalls($model, $modelProperties);

            $migrationPath = $this->generateMigrationFile($table, $migrationCalls);
            $this->info("Migration file generated at {$migrationPath}.");

            return Command::SUCCESS;
        } catch (Throwable $exception) {
            $this->error("Something went wrong while generating the migration file: {$exception->getMessage()}");

            return Command::FAILURE;
        }
    }

    /**
     * @param  class-string  $class
     * @return array<string, ReflectionNamedType|null>
     */
    private function buildModelPropertiesList(string $class): array
    {
        $reflectionClass = new ReflectionClass($class);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        $ignoredProperties = $class::ignoredProperties();
        $result = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if (in_array($propertyName, $ignoredProperties)) {
                continue;
            }

            /** @var ReflectionNamedType|null $type */
            $type = $property->getType() ?? null;
            $result[$propertyName] = $type;
        }

        return $result;
    }

    /**
     * @param  array<string, ReflectionNamedType|null>  $modelProperties
     * @return array<string>
     */
    private function buildMigrationCalls(Model $model, array $modelProperties): array
    {
        $result = [];

        foreach ($modelProperties as $property => $type) {
            if ($property === $model->getKeyName()) {
                $result[] = $this->buildPrimaryKeyMigrationCall($property, $type);

                continue;
            }

            if (blank($type)) {
                $result[] = "\$table->string('{$property}');";

                continue;
            }

            $result[] = match (true) {
                $this->isDateType($type) => $this->generateMigrationCall('timestamp', $type, $property), // @phpstan-ignore-line
                $type->getName() === 'bool' => $this->generateMigrationCall('boolean', $type, $property), // @phpstan-ignore-line
                $type->getName() === 'int' => $this->generateMigrationCall('integer', $type, $property), // @phpstan-ignore-line
                $type->getName() === 'float' => $this->generateMigrationCall('float', $type, $property), // @phpstan-ignore-line
                $type->getName() === 'string' => $this->generateMigrationCall('string', $type, $property), // @phpstan-ignore-line
                $type->getName() === 'array' => $this->generateMigrationCall('json', $type, $property), // @phpstan-ignore-line
                $type->getName() === 'object' => $this->generateMigrationCall('json', $type, $property), // @phpstan-ignore-line
                default => $this->generateMigrationCall('string', $type, $property), // @phpstan-ignore-line
            };
        }

        if ($model->usesTimestamps()) {
            $result[] = '$table->timestamps();';
        }

        if (in_array(SoftDeletes::class, class_uses($model))) {
            $result[] = '$table->softDeletes();';
        }

        return $result;
    }

    private function buildPrimaryKeyMigrationCall(string $property, ?ReflectionNamedType $type): string
    {
        return ! blank($type) && $type->getName() === 'int' // @phpstan-ignore-line
            ? '$table->id();'
            : "\$table->string('{$property}')->primary();";
    }

    private function isDateType(ReflectionNamedType $type): bool
    {
        return in_array($type->getName(), ['Carbon\Carbon', 'Carbon\CarbonImmutable', 'DateTime']);
    }

    private function generateMigrationCall(string $method, ReflectionNamedType $type, string $property): string
    {
        $result = "\$table->{$method}('{$property}')";
        if ($type->allowsNull()) {
            $result .= '->nullable()';
        }

        return "{$result};";
    }

    /**
     * @param  array<string>  $migrationCalls
     */
    private function generateMigrationFile(string $table, array $migrationCalls): string
    {
        $stub = $this->getStub();
        $migrationCalls = implode("\n            ", $migrationCalls);
        $migrationContent = str_replace(['{{TABLE_NAME}}', '{{FIELDS_LIST}}'], [$table, $migrationCalls], $stub);
        $migrationPath = $this->buildMigrationFilePath($table);
        file_put_contents($migrationPath, $migrationContent);

        return $migrationPath;
    }

    private function getStub(): string
    {
        return (string) file_get_contents(__DIR__ . '/../stubs/MigrationCreate.stub');
    }

    private function buildMigrationFilePath(string $table): string
    {
        $timestamp = Carbon::now()->format('Y_m_d_His');

        return $this->laravel->databasePath("migrations/{$timestamp}_create_{$table}_table.php");
    }
}
