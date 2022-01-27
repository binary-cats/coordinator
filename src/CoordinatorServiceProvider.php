<?php

namespace BinaryCats\Coordinator;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CoordinatorServiceProvider extends PackageServiceProvider
{
    /**
     * Configure Coordinator Package.
     *
     * @param  \Spatie\LaravelPackageTools\Package  $package
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('coordinator')
            ->hasConfigFile()
            ->hasMigrations([
                'create_bookings_table',
            ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
        ];
    }
}
