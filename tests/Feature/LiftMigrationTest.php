<?php

declare(strict_types=1);

it('generates a migration file for a model', function () {
    $migrationClass = database_path('migrations/' . date('Y_m_d_His') . '_create_users_migration_table.php');

    $this->artisan('lift:migration UserMigration --namespace=Tests\\\Datasets')
        ->assertExitCode(0);

    expect($migrationClass)->toBeFileWithContent(UserMigration());

    unlink($migrationClass);
});

function UserMigration(): string
{
    return <<<'CLASS'
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
        Schema::create('users_migration', function (Blueprint $table) {
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
        Schema::dropIfExists('users_migration');
    }
};

CLASS;
}
