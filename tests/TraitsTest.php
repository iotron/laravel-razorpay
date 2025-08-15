<?php

use Illuminate\Database\Eloquent\Model;
use Iotron\LaravelRazorpay\Traits\HasRazorpayPayments;
use Iotron\LaravelRazorpay\Traits\HasRazorpayRefunds;

class MockPaymentModel extends Model
{
    use HasRazorpayPayments;
    
    protected $fillable = [
        'provider_gen_id',
        'provider_transaction_id', 
        'provider_generated_sign',
        'verified',
        'type'
    ];
    
    protected $casts = [
        'verified' => 'boolean',
    ];
}

class MockRefundModel extends Model
{
    use HasRazorpayRefunds;
    
    protected $fillable = ['refund_id', 'status', 'amount'];
}

test('HasRazorpayPayments trait provides payment methods', function () {
    $payment = new MockPaymentModel([
        'provider_gen_id' => 'order_123',
        'provider_transaction_id' => 'pay_456',
        'provider_generated_sign' => 'signature_789',
        'verified' => true,
        'type' => 'standard'
    ]);
    
    expect($payment->getRazorpayPaymentId())->toBe('order_123');
    expect($payment->getRazorpayTransactionId())->toBe('pay_456');
    expect($payment->getRazorpaySignature())->toBe('signature_789');
    expect($payment->isPaymentVerified())->toBeTrue();
    expect($payment->getPaymentType())->toBe('standard');
});

test('HasRazorpayPayments trait handles null values', function () {
    $payment = new MockPaymentModel();
    
    expect($payment->getRazorpayPaymentId())->toBeNull();
    expect($payment->getRazorpayTransactionId())->toBeNull();
    expect($payment->getRazorpaySignature())->toBeNull();
    expect($payment->isPaymentVerified())->toBeFalse();
    expect($payment->getPaymentType())->toBe('standard'); // default
});

test('HasRazorpayRefunds trait provides refund methods', function () {
    $refund = new MockRefundModel([
        'refund_id' => 'rfnd_123',
        'status' => 'processed',
        'amount' => 10000
    ]);
    
    expect($refund->getRazorpayRefundId())->toBe('rfnd_123');
    expect($refund->getRefundStatus())->toBe('processed');
    expect($refund->getRefundAmount())->toBe(10000);
});

test('HasRazorpayRefunds trait handles null values', function () {
    $refund = new MockRefundModel();
    
    expect($refund->getRazorpayRefundId())->toBeNull();
    expect($refund->getRefundStatus())->toBe('pending'); // default
    expect($refund->getRefundAmount())->toBe(0); // default
});