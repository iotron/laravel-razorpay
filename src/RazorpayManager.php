<?php

namespace Iotron\LaravelRazorpay;

use Razorpay\Api\Api;
use Iotron\LaravelRazorpay\Actions\OrderAction;
use Iotron\LaravelRazorpay\Actions\PaymentAction;
use Iotron\LaravelRazorpay\Actions\RefundAction;
use Iotron\LaravelRazorpay\Actions\QRAction;

class RazorpayManager
{
    protected Api $api;
    protected array $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->api = new Api(
            $config['key'] ?? null,
            $config['secret'] ?? null
        );
    }
    
    public function orders(): OrderAction
    {
        return new OrderAction($this);
    }
    
    public function payments(): PaymentAction
    {
        return new PaymentAction($this);
    }
    
    public function refunds(): RefundAction
    {
        return new RefundAction($this);
    }
    
    public function qr(): QRAction
    {
        return new QRAction($this);
    }
    
    public function getApi(): Api
    {
        return $this->api;
    }
    
    public function getConfig(string $key = null)
    {
        return $key ? data_get($this->config, $key) : $this->config;
    }
}