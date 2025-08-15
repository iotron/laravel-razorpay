<?php

namespace Iotron\LaravelRazorpay\Actions;

use Iotron\LaravelRazorpay\RazorpayManager;

class OrderAction
{
    protected RazorpayManager $manager;

    public function __construct(RazorpayManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Create a new Razorpay order.
     *
     * @param  array  $data  The data for creating the order.
     * @return mixed Raw Razorpay API response
     */
    public function create(array $data)
    {
        return $this->manager->getApi()->order->create($data);
    }

    /**
     * Fetch a Razorpay order by its identifier.
     *
     * @param  string  $id  The identifier of the order to fetch.
     * @return mixed Raw Razorpay API response
     */
    public function fetch(string $id)
    {
        return $this->manager->getApi()->order->fetch($id);
    }

    /**
     * Retrieve all Razorpay orders.
     *
     * @param  array  $options  Optional parameters to filter the orders.
     * @return mixed Raw Razorpay API response
     */
    public function all(array $options = [])
    {
        return $this->manager->getApi()->order->all($options);
    }
}
