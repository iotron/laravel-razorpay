<?php

use Iotron\LaravelRazorpay\Support\WebhookVerifier;

test('can verify webhook signature manually', function () {
    $secret = 'test_webhook_secret';
    $payload = '{"event":"payment.captured","payload":{"entity":{"id":"pay_123"}}}';
    $expectedSignature = hash_hmac('sha256', $payload, $secret);

    $verifier = new WebhookVerifier($secret);

    expect($verifier->verifyWebhookSignature($payload, $expectedSignature))->toBeTrue();
    expect($verifier->verifyWebhookSignature($payload, 'invalid_signature'))->toBeFalse();
});

test('can parse webhook payload', function () {
    $verifier = new WebhookVerifier('test_secret');
    $payload = '{"event":"payment.captured","payload":{"entity":{"id":"pay_123"}}}';

    $parsed = $verifier->parsePayload($payload);

    expect($parsed)->toBeArray();
    expect($parsed['event'])->toBe('payment.captured');
    expect($parsed['payload']['entity']['id'])->toBe('pay_123');
});

test('throws exception for invalid JSON payload', function () {
    $verifier = new WebhookVerifier('test_secret');

    expect(fn () => $verifier->parsePayload('invalid json'))
        ->toThrow(InvalidArgumentException::class);
});

test('can extract event type from payload', function () {
    $verifier = new WebhookVerifier('test_secret');
    $payload = ['event' => 'payment.captured'];

    expect($verifier->getEventType($payload))->toBe('payment.captured');
    expect($verifier->getEventType([]))->toBeNull();
});

test('can extract entity from payload', function () {
    $verifier = new WebhookVerifier('test_secret');
    $payload = [
        'payload' => [
            'entity' => ['id' => 'pay_123', 'status' => 'captured'],
        ],
    ];

    $entity = $verifier->getEntity($payload);
    expect($entity)->toBeArray();
    expect($entity['id'])->toBe('pay_123');
    expect($entity['status'])->toBe('captured');

    expect($verifier->getEntity([]))->toBeNull();
});
