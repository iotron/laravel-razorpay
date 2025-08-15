<?php

namespace Iotron\LaravelRazorpay\Traits;

trait HasRazorpayRefunds
{
    /**
     * Get the Razorpay refund ID.
     *
     * @return string|null
     */
    public function getRazorpayRefundId(): ?string
    {
        return $this->getRazorpayRefundIdAttribute();
    }
    
    /**
     * Get the refund status.
     *
     * @return string
     */
    public function getRefundStatus(): string
    {
        $value = $this->getRazorpayRefundStatusAttribute();
        return $value instanceof \BackedEnum ? $value->value : (string) $value;
    }
    
    /**
     * Get the refund amount.
     *
     * @return int
     */
    public function getRefundAmount(): int
    {
        return (int) $this->getRazorpayRefundAmountAttribute();
    }
    
    /*
    |--------------------------------------------------------------------------
    | Attribute Methods (Override in your model if fields differ)
    |--------------------------------------------------------------------------
    */
    
    /**
     * Get the Razorpay refund ID attribute.
     * Override this method if your field name is different.
     *
     * @return string|null
     */
    protected function getRazorpayRefundIdAttribute(): ?string
    {
        return $this->refund_id ?? $this->razorpay_refund_id ?? $this->provider_refund_id ?? null;
    }
    
    /**
     * Get the refund status attribute.
     * Override this method if your field name is different.
     *
     * @return mixed
     */
    protected function getRazorpayRefundStatusAttribute()
    {
        return $this->status ?? $this->refund_status ?? 'pending';
    }
    
    /**
     * Get the refund amount attribute.
     * Override this method if your field name is different.
     *
     * @return mixed
     */
    protected function getRazorpayRefundAmountAttribute()
    {
        return $this->amount ?? $this->refund_amount ?? 0;
    }
}