<?php

namespace Iotron\LaravelRazorpay\Traits;

trait HasRazorpayPayments
{
    /**
     * Get the Razorpay payment/order ID.
     */
    public function getRazorpayPaymentId(): ?string
    {
        return $this->getRazorpayPaymentIdAttribute();
    }

    /**
     * Get the Razorpay transaction ID.
     */
    public function getRazorpayTransactionId(): ?string
    {
        return $this->getRazorpayTransactionIdAttribute();
    }

    /**
     * Get the Razorpay signature.
     */
    public function getRazorpaySignature(): ?string
    {
        return $this->getRazorpaySignatureAttribute();
    }

    /**
     * Check if the payment is verified.
     */
    public function isPaymentVerified(): bool
    {
        return (bool) $this->getRazorpayVerifiedAttribute();
    }

    /**
     * Get the payment type.
     */
    public function getPaymentType(): string
    {
        $value = $this->getRazorpayPaymentTypeAttribute();

        return $value instanceof \BackedEnum ? $value->value : (string) $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute Methods (Override in your model if fields differ)
    |--------------------------------------------------------------------------
    */

    /**
     * Get the Razorpay payment ID attribute.
     * Override this method if your field name is different.
     */
    protected function getRazorpayPaymentIdAttribute(): ?string
    {
        return $this->provider_gen_id ?? $this->razorpay_payment_id ?? $this->razorpay_order_id ?? null;
    }

    /**
     * Get the Razorpay transaction ID attribute.
     * Override this method if your field name is different.
     */
    protected function getRazorpayTransactionIdAttribute(): ?string
    {
        return $this->provider_transaction_id ?? $this->razorpay_transaction_id ?? null;
    }

    /**
     * Get the Razorpay signature attribute.
     * Override this method if your field name is different.
     */
    protected function getRazorpaySignatureAttribute(): ?string
    {
        return $this->provider_generated_sign ?? $this->provider_gen_sign ?? $this->razorpay_signature ?? null;
    }

    /**
     * Get the payment verified attribute.
     * Override this method if your field name is different.
     */
    protected function getRazorpayVerifiedAttribute(): bool
    {
        return $this->verified ?? $this->is_verified ?? false;
    }

    /**
     * Get the payment type attribute.
     * Override this method if your field name is different.
     *
     * @return mixed
     */
    protected function getRazorpayPaymentTypeAttribute()
    {
        return $this->type ?? $this->payment_type ?? 'standard';
    }
}
