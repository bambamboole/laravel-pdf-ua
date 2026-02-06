<?php

namespace Bambamboole\LaravelPdfUa;

use Bambamboole\LaravelPdfUa\Commands\GeneratePdfUaCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PdfUaServiceProvider extends PackageServiceProvider
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
            ->hasCommand(GeneratePdfUaCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('pdf-ua', function ($app) {
            return new PdfUaGenerator(config('laravel-pdf-ua', []));
        });
    }
}
