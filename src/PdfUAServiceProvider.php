<?php

namespace Bambamboole\PdfUA;

use Bambamboole\PdfUA\Commands\PdfUACommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PdfUAServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-pdf-ua')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_pdf_ua_table')
            ->hasCommand(PdfUACommand::class);
    }
}
