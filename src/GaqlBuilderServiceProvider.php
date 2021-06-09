<?php

namespace MkRyan1988\GaqlBuilder;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MkRyan1988\GaqlBuilder\Commands\GaqlBuilderCommand;

class GaqlBuilderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('elequent-gaql')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_elequent-gaql_table')
            ->hasCommand(GaqlBuilderCommand::class);
    }
}
