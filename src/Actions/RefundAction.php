<?php

namespace Iotron\LaravelRazorpay\Actions;

use Iotron\LaravelRazorpay\RazorpayManager;

class RefundAction
{
    protected RazorpayManager $manager;

    public function __construct(RazorpayManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Create a new refund.
     *
     * @param  string  $paymentId  The payment ID to refund.
     * @param  array  $data  Refund data (amount, speed, notes, etc).
     * @return mixed Raw Razorpay API response
     */
    public function create(string $paymentId, array $data = [])
    {
        return $this->manager->getApi()->payment->fetch($paymentId)->refund($data);
    }

    /**
     * Fetch a refund by its ID.
     *
     * @param  string  $id  The refund ID.
     * @return mixed Raw Razorpay API response
     */
    public function fetch(string $id)
    {
        return $this->manager->getApi()->refund->fetch($id);
    }

    /**
     * Fetch all refunds.
     *
     * @param  array  $options  Optional parameters to filter refunds.
     * @return mixed Raw Razorpay API response
     */
    public function all(array $options = [])
    {
        return $this->manager->getApi()->refund->all($options);
    }
}
