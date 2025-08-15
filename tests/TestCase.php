<?php

namespace Iotron\LaravelRazorpay\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Iotron\LaravelRazorpay\RazorpayServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            RazorpayServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        
        // Set test config
        config()->set('razorpay.key', 'test_key');
        config()->set('razorpay.secret', 'test_secret');
        config()->set('razorpay.webhook_secret', 'test_webhook_secret');
    }
}