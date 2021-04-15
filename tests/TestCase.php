<?php

namespace BinaryCats\Coordinator\Tests;

use BinaryCats\Coordinator\CoordinatorServiceProvider;
use BinaryCats\Coordinator\Tests\Models\BookableResourceModel;
use BinaryCats\Coordinator\Tests\Models\CanBookResourcesModel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
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
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            CoordinatorServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
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
        include_once __DIR__.'/../database/migrations/create_bookings_table.php.stub';

        (new \CreateBookingsTable())->up();
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
            ExceptionHandler::class, new class extends Handler {
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

    protected function seedModels(...$modelClasses)
    {
        collect($modelClasses)->each(function (string $modelClass) {
            foreach (range(1, 0) as $index) {
                $modelClass::create([]);
            }
        });
    }
}
