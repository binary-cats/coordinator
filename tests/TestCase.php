<?php

namespace Tests;

use BinaryCats\Coordinator\CoordinatorServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tests\Models\BookableResourceModel;
use Tests\Models\CanBookResourcesModel;
use Throwable;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            CoordinatorServiceProvider::class,
        ];
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * @return void
     */
    protected function setUpDatabase(): void
    {
        $migration = include __DIR__.'/../database/migrations/create_bookings_table.php.stub';

        $migration->up();
        // create fake tables
        $this->createTables('bookable_resource_models', 'can_book_resources_models');
        $this->seedModels(BookableResourceModel::class, CanBookResourcesModel::class);
    }

    /**
     * Disable Exception Handling.
     *
     * @return void
     */
    protected function disableExceptionHandling(): void
    {
        $this->app->instance(
            ExceptionHandler::class, new class extends Handler
            {
                public function __construct()
                {
                }

                public function report(Throwable $e)
                {
                }

                public function render($request, Throwable $exception)
                {
                    throw $exception;
                }
            }
        );
    }

    /**
     * @param ...$tableNames
     * @return void
     */
    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function (string $tableName) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->softDeletes();
            });
        });
    }

    /**
     * @param ...$modelClasses
     * @return void
     */
    protected function seedModels(...$modelClasses)
    {
        collect($modelClasses)->each(function (string $modelClass) {
            foreach (range(1, 0) as $index) {
                $modelClass::create([]);
            }
        });
    }
}
