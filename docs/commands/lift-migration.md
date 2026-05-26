# lift:migration

> ⚠️ **This is an experimental feature, keep that in mind when using it**

The `lift:migration` command allows you to generate a migration file based on your models. By default, it uses the `App\Models` namespace, but you can change it using the `--namespace` option.

All the created migration files will be placed inside the `database/migrations` folder.

## Examples

The command below will generate a migration file for the `App\Models\User` model.

```bash
php artisan lift:migration User
```

The command below will generate a migration file for the `App\Models\Auth\User` model.

```bash
php artisan lift:migration Auth\User
```

The command below will generate a migration file for the `App\Custom\Models\User` model.

```bash
php artisan lift:migration User --namespace=App\Custom\Models
```

## Create Table Migration

When the table for your model is not yet created in the database, the `lift:migration` command will generate a migration file to create the table.

```php
// User.php

final class User extends Model
{
    use Lift, SoftDeletes;

    public int $id;

    public string $name;

    public string $email;

    public string $password;

    public CarbonImmutable $created_at;

    public DateTime $updated_at;

    public ?bool $active;

    public $test;
}

// Migration file generated

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->boolean('active')->nullable();
            $table->string('test');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

## Update Table Migration

When the table for your model is already created in the database, the `lift:migration` command will generate a migration file to update the table based on the differences between the model and the database table.

```php
// User.php

final class User extends Model
{
    use Lift, SoftDeletes;

    public int $id;

    public string $name;

    public string $username;

    public string $email;

    public string $password;

    public ?bool $active;
}

// Migration file generated

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('name');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->dropColumn('test');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do here
    }
};
```
