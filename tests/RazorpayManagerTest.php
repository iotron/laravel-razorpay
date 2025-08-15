<?php

use Iotron\LaravelRazorpay\RazorpayManager;
use Iotron\LaravelRazorpay\Actions\OrderAction;
use Iotron\LaravelRazorpay\Actions\PaymentAction;
use Iotron\LaravelRazorpay\Actions\RefundAction;
use Iotron\LaravelRazorpay\Actions\QRAction;

test('can instantiate RazorpayManager', function () {
    $manager = app(RazorpayManager::class);
    
    expect($manager)->toBeInstanceOf(RazorpayManager::class);
});

test('provides access to all action classes', function () {
    $manager = app(RazorpayManager::class);
    
    expect($manager->orders())->toBeInstanceOf(OrderAction::class);
    expect($manager->payments())->toBeInstanceOf(PaymentAction::class);
    expect($manager->refunds())->toBeInstanceOf(RefundAction::class);
    expect($manager->qr())->toBeInstanceOf(QRAction::class);
});

test('provides access to Razorpay API instance', function () {
    $manager = app(RazorpayManager::class);
    
    expect($manager->getApi())->toBeInstanceOf(\Razorpay\Api\Api::class);
});

test('can access configuration', function () {
    $manager = app(RazorpayManager::class);
    
    $config = $manager->getConfig();
    expect($config)->toBeArray();
    expect($config['key'])->toBe('test_key');
    expect($config['secret'])->toBe('test_secret');
    
    expect($manager->getConfig('key'))->toBe('test_key');
    expect($manager->getConfig('secret'))->toBe('test_secret');
});