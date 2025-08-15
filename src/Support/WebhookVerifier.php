<?php

namespace Iotron\LaravelRazorpay\Support;

use Razorpay\Api\Api;

class WebhookVerifier
{
    protected string $webhookSecret;
    protected ?Api $api;
    
    /**
     * Create a new WebhookVerifier instance.
     *
     * @param string $webhookSecret The webhook secret from Razorpay dashboard
     * @param Api|null $api Optional Razorpay API instance for payment signature verification
     */
    public function __construct(string $webhookSecret, Api $api = null)
    {
        $this->webhookSecret = $webhookSecret;
        $this->api = $api;
    }
    
    /**
     * Verify webhook signature.
     *
     * @param string $payload The raw request body
     * @param string $signature The X-Razorpay-Signature header value
     * @return bool
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        if ($this->api) {
            try {
                $this->api->utility->verifyWebhookSignature($payload, $signature, $this->webhookSecret);
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
        
        // Fallback to manual verification if API not provided
        $expectedSignature = hash_hmac('sha256', $payload, $this->webhookSecret);
        return hash_equals($expectedSignature, $signature);
    }
    
    /**
     * Verify payment signature for callbacks.
     *
     * @param array $attributes Should contain razorpay_order_id, razorpay_payment_id, razorpay_signature
     * @return bool
     */
    public function verifyPaymentSignature(array $attributes): bool
    {
        if (!$this->api) {
            throw new \RuntimeException('Razorpay API instance required for payment signature verification');
        }
        
        try {
            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Parse webhook payload.
     *
     * @param string $payload The raw request body
     * @return array
     */
    public function parsePayload(string $payload): array
    {
        $data = json_decode($payload, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON payload: ' . json_last_error_msg());
        }
        
        return $data;
    }
    
    /**
     * Get event type from parsed webhook payload.
     *
     * @param array $payload The parsed webhook payload
     * @return string|null
     */
    public function getEventType(array $payload): ?string
    {
        return $payload['event'] ?? null;
    }
    
    /**
     * Get entity from parsed webhook payload.
     *
     * @param array $payload The parsed webhook payload
     * @return array|null
     */
    public function getEntity(array $payload): ?array
    {
        return $payload['payload']['entity'] ?? null;
    }
}