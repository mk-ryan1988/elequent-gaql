<?php

namespace MkRyan1988\EloquentGaql\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use MkRyan1988\EloquentGaql\GaqlBuilderServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'MkRyan1988\\GaqlBuilder\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            GaqlBuilderServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        include_once __DIR__.'/../database/migrations/create_elequent-gaql_table.php.stub';
        (new \CreatePackageTable())->up();
        */
    }
}
