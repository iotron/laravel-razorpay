<?php

namespace Iotron\LaravelRazorpay\Actions;

use Iotron\LaravelRazorpay\RazorpayManager;

class PaymentAction
{
    protected RazorpayManager $manager;
    
    public function __construct(RazorpayManager $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * Fetch a payment by its ID.
     *
     * @param string $id The payment ID.
     * @return mixed Raw Razorpay API response
     */
    public function fetch(string $id)
    {
        return $this->manager->getApi()->payment->fetch($id);
    }
    
    /**
     * Fetch all payments.
     *
     * @param array $options Optional parameters to filter payments.
     * @return mixed Raw Razorpay API response
     */
    public function all(array $options = [])
    {
        return $this->manager->getApi()->payment->all($options);
    }
    
    /**
     * Capture a payment.
     *
     * @param string $id The payment ID.
     * @param array $data Capture data (amount, currency, etc).
     * @return mixed Raw Razorpay API response
     */
    public function capture(string $id, array $data)
    {
        return $this->manager->getApi()->payment->fetch($id)->capture($data);
    }
    
    /**
     * Refund a payment.
     *
     * @param string $id The payment ID.
     * @param array $data Refund data.
     * @return mixed Raw Razorpay API response
     */
    public function refund(string $id, array $data = [])
    {
        return $this->manager->getApi()->payment->fetch($id)->refund($data);
    }
}