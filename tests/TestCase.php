<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->foreignId('country_id')->nullable()->constrained();
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        Schema::create('users_guarded', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('users_uuid', function (Blueprint $table) {
            $table->string('uuid')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('users_custom', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('users_null', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price');
            $table->integer('random_number');
            $table->integer('another_random_number')->nullable();
            $table->json('json_column')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->float('content');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('role_id')->constrained();
            $table->timestamps();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('computers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('computer_id')->nullable()->constrained();
            $table->timestamps();
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained();
            $table->morphs('taggable');
            $table->timestamps();
        });

        Schema::create('book_cases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_case_id')->nullable()->constrained();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('library_id')->nullable()->constrained();
            $table->foreignId('book_case_id')->nullable()->constrained();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('work_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('book_case_id')->nullable()->constrained();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_id')->nullable()->constrained();
            $table->decimal('price');
            $table->timestamps();
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->string('movie_id')->primary();
            $table->timestamps();
        });

        Schema::create('users_migrated', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('crews', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });

        Schema::create('category_refresheds', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('sort')->default(9999);
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app)
    {
        return ['WendellAdriel\Lift\Providers\LiftServiceProvider'];
    }
}
