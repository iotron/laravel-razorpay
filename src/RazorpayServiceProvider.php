<?php

namespace Iotron\LaravelRazorpay;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RazorpayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-razorpay')
            ->hasConfigFile();
    }

    public function registeringPackage(): void
    {
        $this->app->singleton(RazorpayManager::class, function ($app) {
            return new RazorpayManager($app['config']['razorpay'] ?? []);
        });
    }
}