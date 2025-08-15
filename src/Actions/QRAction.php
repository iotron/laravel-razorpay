<?php

namespace Iotron\LaravelRazorpay\Actions;

use Iotron\LaravelRazorpay\RazorpayManager;

class QRAction
{
    protected RazorpayManager $manager;

    public function __construct(RazorpayManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Create a new QR code.
     *
     * @param  array  $data  QR code data (amount, type, usage, etc).
     * @return mixed Raw Razorpay API response
     */
    public function create(array $data)
    {
        return $this->manager->getApi()->qrCode->create($data);
    }

    /**
     * Fetch a QR code by its ID.
     *
     * @param  string  $id  The QR code ID.
     * @return mixed Raw Razorpay API response
     */
    public function fetch(string $id)
    {
        return $this->manager->getApi()->qrCode->fetch($id);
    }

    /**
     * Fetch all QR codes.
     *
     * @param  array  $options  Optional parameters to filter QR codes.
     * @return mixed Raw Razorpay API response
     */
    public function all(array $options = [])
    {
        return $this->manager->getApi()->qrCode->all($options);
    }

    /**
     * Close a QR code.
     *
     * @param  string  $id  The QR code ID to close.
     * @return mixed Raw Razorpay API response
     */
    public function close(string $id)
    {
        return $this->manager->getApi()->qrCode->fetch($id)->close();
    }

    /**
     * Fetch all payments for a QR code.
     *
     * @param  string  $id  The QR code ID.
     * @return mixed Raw Razorpay API response
     */
    public function fetchAllPayments(string $id)
    {
        return $this->manager->getApi()->qrCode->fetch($id)->fetchAllPayments();
    }
}
